<?php
// Start the session
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

$config = include('../config.php');
// Database connection details
$host = $config['db_host'];
$dbname = $config['db_name'];
$username = $config['db_user'];
$password = $config['db_pass'];

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL to add new columns 'app_url' and 'cloud_url'
    $sql = "ALTER TABLE db_admin 
            ADD COLUMN app_url TEXT NOT NULL, 
            ADD COLUMN cloud_url TEXT NOT NULL";
    
    // Execute the query
    $pdo->exec($sql);
    
    echo "Columns 'app_url' and 'cloud_url' successfully added.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the connection
$pdo = null;

// Redirect to a specific page after logout (e.g., homepage or login page)
header("Location: /"); // Replace with your desired redirect URL
exit;
