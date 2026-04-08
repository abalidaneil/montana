<?php
session_start();

// 1. Database Configuration
require_once "sqli.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Check if login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve email and password from the login form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 3. Prepare query to find the user
    // We select the password hash and user info from the 'users' table
    $stmt = $conn->prepare("SELECT id, fname, lname, password, balance FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 4. Verify the password hash
        if (password_verify($password, $user['password'])) {
            // Success! Create the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_fname'] = $user['fname'];
            $_SESSION['user_lname'] = $user['lname'];
            $_SESSION['user_balance'] = $user['balance'];
            
            // Redirect to your future dashboard
            header("Location: ../dashboard.php"); 
            exit();
        } else {
            // Invalid password
            header("Location: ../login.html?error=invalid_credentials");
            exit();
        }
    } else {
        // User not found
        header("Location: ../login.html?error=not_found");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>