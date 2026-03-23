<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.html"); exit(); }

$host = "localhost"; $user = "root"; $pass = ""; $dbname = "montana";
$conn = new mysqli($host, $user, $pass, $dbname);

// --- READ: Fetching all the data for the "Comprehensive" view ---
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$loans = $conn->query("SELECT l.*, u.fname, u.lname FROM loans l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC");
$withdraws = $conn->query("SELECT w.*, u.fname, u.lname FROM withdrawals w JOIN users u ON w.user_id = u.id ORDER BY w.created_at DESC");

// Stats for the top cards
$total_on_site = $conn->query("SELECT SUM(balance) as total FROM users")->fetch_assoc()['total'];
$user_count = $users->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Firstworldchoice | Super Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-bg: #0d3b36; --main-bg: #f4f7f6; --accent: #d1f366; }
        body { margin: 0; display: flex; font-family: 'Inter', sans-serif; background: var(--main-bg); }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; padding: 20px; }
        .sidebar h2 { color: var(--accent); margin-bottom: 40px; }
        .nav-link { display: block; padding: 15px; color: #a0b1ad; text-decoration: none; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }

        /* Main Content Area */
        .content { margin-left: 300px; padding: 40px; width: calc(100% - 300px); }
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; border-bottom: 4px solid var(--sidebar-bg); }

        /* CRUD Tables */
        .section-card { background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #666; font-size: 0.85rem; }
        td { padding: 12px; border-bottom: 1px solid #f9f9f9; font-size: 0.9rem; }

        /* Action Buttons */
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold; }
        .btn-edit { background: #e0f2fe; color: #0369a1; }
        .btn-delete { background: #fee2e2; color: #b91c1c; }
        .btn-approve { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>F.W.C Admin</h2>
        <a href="#users" class="nav-link active"><i class="fa fa-users"></i> Users</a>
        <a href="#loans" class="nav-link"><i class="fa fa-hand-holding-dollar"></i> Loans</a>
        <a href="admin_inbox.php" class="nav-link"><i class="fa fa-money-bill-transfer"></i> Chats</a>
        <a href="backend/logout.php" class="nav-link" style="margin-top:50px;"><i class="fa fa-sign-out"></i> Logout</a>
    </div>

    <div class="content">
        <div class="stats-row">
            <div class="stat-card"><h3>Total Users</h3><p><?php echo $user_count; ?></p></div>
            <div class="stat-card"><h3>Global Balance</h3><p>$<?php echo number_format($total_on_site, 2); ?></p></div>
            <div class="stat-card"><h3>System Status</h3><p style="color:green">Online</p></div>
        </div>

        <div class="section-card" id="users">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Manage Users</h2>
                <a href="admin_add_user.php" class="btn btn-approve">+ Add New User</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['fname'].' '.$row['lname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><strong>$<?php echo number_format($row['balance'], 2); ?></strong></td>
                        <td>
                            <a href="admin_edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Update</a>
                            <a href="backend/admin_delete.php?type=user&id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete user and all their history?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="section-card" id="loans">
            <h2>Loan Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($l = $loans->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $l['fname'].' '.$l['lname']; ?></td>
                        <td>$<?php echo number_format($l['amount'], 2); ?></td>
                        <td><?php echo date('M d, Y', strtotime($l['created_at'])); ?></td>
                        <td><span style="color:orange"><?php echo $l['status']; ?></span></td>
                        <td>
                            <a href="backend/admin_actions.php?action=approve_loan&id=<?php echo $l['id']; ?>" class="btn btn-approve">Verify</a>
                            <a href="backend/admin_delete.php?type=loan&id=<?php echo $l['id']; ?>" class="btn btn-delete">Clear</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>