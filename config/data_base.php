<?php
// Database connection using PDO
$host = 'localhost';
$db = 'dealhub';
$user = 'root'; // Default XAMPP username
$pass = ''; // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
