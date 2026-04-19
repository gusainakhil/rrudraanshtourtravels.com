<?php
$host = '68.178.236.80';
$dbname = 'travel';
$username = 'travel';
$password = 'ch!CK5ZlhTHP';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>