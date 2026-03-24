<?php
session_start();

// 1. Security Check: If the user is not logged in, send them back to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// 2. Assign session variables for easier use
$firstName = $_SESSION['user_fname'];
$lastName = $_SESSION['user_lname'];
$balance = number_format($_SESSION['user_balance'], 2); // Formats as 0.00
?>

<?php
// Ensure session is started and DB connection exists
$userId = $_SESSION['user_id'];
$query = $conn->query("SELECT verify_status FROM users WHERE id = $userId");
$userData = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Firstworldchoice - Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
     <link rel="stylesheet" href="styles/main.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-container">
            <i class="fa-solid fa-building-columns logo-icon"></i>
            <span style="font-weight: 700; font-size: 20px;">FirstWorld</span>
        </div>
        <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-layer-group"></i> Dashboard <i class="fa-solid fa-chevron-right arrow"></i></a>
        <a href="loan.php" class="nav-item"><i class="fa-solid fa-laptop-code"></i> Loans <i class="fa-solid fa-chevron-right arrow"></i></a>
        <a href="fund.php" class="nav-item"><i class="fa-solid fa-sliders"></i> Fund Account <i class="fa-solid fa-chevron-right arrow"></i></a>
        <a href="withdraw.html" class="nav-item"><i class="fa-solid fa-sliders"></i> Withdrawal <i class="fa-solid fa-chevron-down arrow"></i></a>
        <a href="trans.html" class="nav-item"><i class="fa-solid fa-earth-americas"></i> Transfer <i class="fa-solid fa-chevron-right arrow"></i></a>
        <a href="backend/logout.php" class="nav-item"><i class="fa-solid fa-table-cells-large"></i> Logout <i class="fa-solid fa-chevron-right arrow"></i></a>
    </div>

    <main>
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Dashboard</h2>
            <div style="font-size: 0.9rem;"><a href="profile.html" style="color: black;"> Hello, <strong><?php echo htmlspecialchars($firstName); ?></strong> </a></div>
        </header>

        <?php if ($userData['verify_status'] === 'Unverified'): ?>
            <div class="verification-alert" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #ffeeba;">
                <i class="fa-solid fa-circle-exclamation"></i> 
                Your account is not yet <strong>verified</strong>. 
                <a href="verify.php" style="color: #856404; font-weight: bold; text-decoration: underline;">Click here to continue</a>
            </div>
        <?php endif; ?>

        <div class="main-card">
            <div class="btn-plus">+</div>
            <p style="color: #666;">Welcome <span style="color: red;"><?php echo htmlspecialchars($firstName); ?> <?php echo htmlspecialchars($lastName); ?> ,</span></p>
            <p style="font-size: 0.8rem; color: #999;">Total Balance</p>
            <div class="balance-text">$ <?php echo $balance; ?></div>
            
            <div class="payment-methords">
                <div><div style="color: red;">↓</div><small>Bank</small></div>
                <div><div style="color: blue;">G</div><small>GCash</small></div>
                <div><div style="color: #0070ba;">P</div><small>PayPal</small></div>
                <div><div style="color: #800080;">S</div><small>Skrill</small></div>
            </div>
        </div>

        <div class="grid-stats">
            <div class="stat-card"><small>Current Balance</small><div>$ <?php echo $balance; ?></div></div>
            <div class="stat-card"><small>Ledger Balance</small><div>$ <?php echo $balance; ?></div></div>
            <div class="stat-card"><small>Available Balance</small><div>$ <?php echo $balance; ?></div></div>
            <div class="stat-card"><small>Refundable Balance</small><div>$ 0.00</div></div>
        </div>

        <div class="overview-grid">
            <div class="color-card" style="background: #a855f7;">
                <div class="circle-progress">0%</div>
                <span>Withdrawals</span>
            </div>
            <div class="color-card" style="background: #22c55e;">
                <div class="circle-progress">0%</div>
                <span>Transfers</span>
            </div>
        </div>

        <div class="overview-grid">
            <div class="color-card" style="background: #22c55e;">
                <div class="circle-progress">0%</div>
                <span>Withdrawals</span>
            </div>
            <div class="color-card" style="background: #a855f7;">
                <div class="circle-progress">0%</div>
                <span>Transfers</span>
            </div>
        </div>
    </main>

    <div class="chat-btn">
        <div style="width: 10px; height: 10px; background: #2ecc71; border-radius: 50%;"></div>
        <a href="chat.php">Chat</a> <i class="fa-solid fa-comment"></i>
    </div>

</body>
</html>