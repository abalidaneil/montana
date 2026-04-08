<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// session_start();

// $host = 'fdb1032.awardspace.net';
// $dbname = '4676457_montana';
// $username = '4676457_montana';
// $password = 'FdgO%Ct]4[kmV7T[';

$host = 'localhost';
$dbname = 'montana';
$username = 'root';
$password = '';

function getDatabaseConnection($host, $dbname, $username, $password) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

$pdo = getDatabaseConnection($host, $dbname, $username, $password);

// Optionally, you can close the connection
// $pdo = null;

?>
