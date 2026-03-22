<?php
session_start();
if (!isset($_SESSION['admin_id'])) exit();

$conn = new mysqli("localhost", "root", "", "montana");

$type = $_GET['type'];
$id = (int)$_GET['id'];

if ($type == 'user') {
    $conn->query("DELETE FROM users WHERE id = $id");
} elseif ($type == 'loan') {
    $conn->query("DELETE FROM loans WHERE id = $id");
}

header("Location: ../admin_dashboard.php");
?>