<?php
session_start();
$host = "localhost"; $user = "root"; $pass = ""; $dbname = "montana";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Check if password matches the hash
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $username;
            header("Location: ../admin_dashboard.php");
            exit();
        }
    }
    // Redirect back if failed
    header("Location: ../admin_login.html?error=failed");
}
?>