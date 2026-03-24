<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }

$host = "fdb1032.awardspace.net"; $user = "4676457_montana"; $pass = "FdgO%Ct]4[kmV7T["; $dbname = "4676457_montana";
$conn = new mysqli($host, $user, $pass, $dbname);
$userId = $_SESSION['user_id'];

// Fetch Loan History
$historyQuery = "SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC";
$stmtH = $conn->prepare($historyQuery);
$stmtH->bind_param("i", $userId);
$stmtH->execute();
$history = $stmtH->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Firstworldchoice - Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/loan.css">
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

    <div class="main">
        <h1>Loan Management</h1>

        <div class="loan-form">
            <h3>Request a New Loan</h3>
            <form action="backend/process_loan.php" method="POST">
                <input type="number" name="loan_amount" placeholder="Enter Amount" required min="100">
                <button type="submit">Demand Loan</button>
            </form>
        </div>

        <div class="card-grid">
            <div class="card">
                <div><h3>$ <?php echo number_format($_SESSION['user_balance'], 2); ?></h3><p>Current Balance</p></div>
                <i class="fas fa-wallet" style="color:orange"></i>
            </div>
            <div class="card">
                <div><h3>Eligible</h3><p>Status</p></div>
                <i class="fas fa-check-circle" style="color:green"></i>
            </div>
        </div>

        <div class="history-section">
            <h2>Loan History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($history->num_rows > 0): ?>
                        <?php while($row = $history->fetch_assoc()): ?>
                        <tr>
                            <td>$<?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td style="color: green; font-weight: bold;"><?php echo $row['status']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No loan history found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>