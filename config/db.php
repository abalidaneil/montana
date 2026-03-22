<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$host = 'localhost';
$dbname = 'montana';
$username = 'root';
$password = ''; // Store this securely, e.g., in environment variables

function getDatabaseConnection($host, $dbname, $username, $password) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Establish the connection
$pdo = getDatabaseConnection($host, $dbname, $username, $password);
echo("connection successfull")

// Optionally, you can close the connection
// $pdo = null;

?>
