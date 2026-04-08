<?php
session_start();

// 1. Database Configuration
require_once "sqli.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize basic inputs
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = (int)$_POST['phone']; // Database column is int(11)
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $password = $_POST['password'];
    $cpass = $_POST['cpass'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = mysqli_real_escape_string($conn, $_POST['countries']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $type = mysqli_real_escape_string($conn, $_POST['acc-type']);
    $discription = mysqli_real_escape_string($conn, $_POST['discription']);

    // 3. Validation
    if ($password !== $cpass) {
        die("Passwords do not match!");
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        die("An account with this email already exists.");
    }

    // 4. Password Hashing
    // NOTE: Your DB column 'password' is varchar(50). 
    // password_hash() usually requires 60 characters. 
    // I recommend running: ALTER TABLE users MODIFY password VARCHAR(255);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // $hashed_password = $password;
    // 5. Prepare Insert Statement
    // Columns matched to your phpMyAdmin screenshot
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, phone, gender, password, address, country, state, zip, type, discription, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0.00)");
    
    $stmt->bind_param("sssissssssss", 
        $fname, 
        $lname, 
        $email, 
        $phone, 
        $gender, 
        $hashed_password, 
        $address, 
        $country, 
        $state, 
        $zip, 
        $type, 
        $discription
    );

    // 6. Execute and Redirect
    if ($stmt->execute()) {
        // Log the user in automatically or set a success message
        $_SESSION['user_email'] = $email;
        $_SESSION['user_fname'] = $fname;

        // Redirect to a dashboard or homepage
        header("Location: ../login.html?signup=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>