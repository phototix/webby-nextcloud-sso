<?php
// conn.php - Database connection file

// Load DB credentials from external config file
$config = include('../config.php');

try {
    // Create a new PDO connection
    $pdo = new PDO(
        "mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'],
        $config['db_user'],
        $config['db_pass']
    );

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
