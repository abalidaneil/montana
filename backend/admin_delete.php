<?php
session_start();
if (!isset($_SESSION['admin_id'])) { exit("Unauthorized"); }

$host = "fdb1032.awardspace.net"; $user = "4676457_montana"; $pass = "FdgO%Ct]4[kmV7T["; $dbname = "4676457_montana";
$conn = new mysqli($host, $user, $pass, $dbname);
$id = $_GET['id'];

if (isset($id)) {
    // This will also delete their loans/withdrawals if you set up "CASCADE" in the DB
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: ../admin_dashboard.php?msg=deleted");
?>