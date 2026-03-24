<?php
session_start();
if (!isset($_SESSION['admin_id'])) { die("Access Denied"); }
$host = "fdb1032.awardspace.net"; $user = "4676457_montana"; $pass = "FdgO%Ct]4[kmV7T["; $dbname = "4676457_montana";
$conn = new mysqli($host, $user, $pass, $dbname);

$action = $_GET['action'];
$id = $_GET['id'];
$status = $_GET['status'];

if ($action == 'verify') {
    // Update User Verification Status
    $stmt = $conn->prepare("UPDATE users SET verify_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
} 

if ($action == 'withdraw') {
    // Update Withdrawal Request Status
    $stmt = $conn->prepare("UPDATE withdrawals SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}

header("Location: ../admin_dashboard.php?msg=success");
exit();
?>