<?php

declare(strict_types=1);

$host = '68.178.236.80';
$dbname = 'travel';
$username = 'travel';
$password = 'ch!CK5ZlhTHP';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    throw new RuntimeException('Database connection is not available.');
}
