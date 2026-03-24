<?php
// STEP 1: FORCE ERRORS TO SHOW (This stops the Error 500 and shows the real problem)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// STEP 2: DATABASE CONNECTION
// Double-check these 4 values match your XAMPP/WAMP settings
$host = "localhost";
$user = "root";
$pass = "";
$db   = "montana";

$conn = new mysqli($host, $user, $pass, $db);

// If the database is the problem, this will tell us
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// STEP 3: SECURITY CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id'];
$firstName = $_SESSION['user_fname'] ?? 'User';
$lastName = $_SESSION['user_lname'] ?? '';
$balance = number_format($_SESSION['user_balance'] ?? 0, 2);

// STEP 4: FETCH VERIFY STATUS
$query = $conn->query("SELECT verify_status FROM users WHERE id = $userId");
$userData = ($query) ? $query->fetch_assoc() : ['verify_status' => 'Unverified'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Firstworldchoice - Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <i class="fa-solid fa-building-columns logo-icon"></i>
            <span style="font-weight: 700; font-size: 20px;">FirstWorld</span>
        </div>
        <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-layer-group"></i> Dashboard</a>
        <a href="loan.php" class="nav-item"><i class="fa-solid fa-laptop-code"></i> Loans</a>
        <a href="fund.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Fund Account</a>
        <a href="withdraw.html" class="nav-item"><i class="fa-solid fa-money-bill-transfer"></i> Withdrawal</a>
        <a href="trans.html" class="nav-item"><i class="fa-solid fa-earth-americas"></i> Transfer</a>
        <a href="backend/logout.php" class="nav-item"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <main>
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Dashboard</h2>
            <div>Hello, <strong><?php echo htmlspecialchars($firstName); ?></strong></div>
        </header>

        <?php if ($userData['verify_status'] === 'Unverified'): ?>
            <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #ffeeba;">
                Your account is not yet verified. <a href="info.html">Click here to continue</a>
            </div>
        <?php endif; ?>

        <div class="main-card">
            <p>Welcome <span style="color: red;"><?php echo htmlspecialchars($firstName . " " . $lastName); ?></span></p>
            <div style="font-size: 2rem; font-weight: bold;">$ <?php echo $balance; ?></div>
        </div>
    </main>
</body>
</html>