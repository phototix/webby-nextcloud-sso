<?php
// conn.php - Database connection file

// Load DB credentials from external config file
$config = include('config.php');

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
} // This assumes you have a conn.php file for DB connection

try {
    // Check if the email exists in the db_admin table
    $stmt = $pdo->prepare("SELECT * FROM db_admin WHERE admin_email = :admin_email");
    $stmt->execute(['admin_email' => $api]);
    $userRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    $api_url = $userRecord['app_url'];
    $customAccess = true;

    if($api_url=="https://member.webbypage.com"){
        // Load redirect page
        include('dashboard.php');
    }else{
        // Load redirect page
        include('continue.php');
    }

} catch (PDOException $e) {
    $customAccess = false;
    $api_url = "https://member.webbypage.com?no_url";

    // Load redirect page
    include('dashboard.php');
}
?>

