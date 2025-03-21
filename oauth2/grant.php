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
} // This assumes you have a conn.php file for DB connection

try {
    // Check if the email exists in the db_admin table
    $stmt = $pdo->prepare("SELECT * FROM db_admin WHERE admin_email = :admin_email");
    $stmt->execute(['admin_email' => $api]);
    $userRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    $api_url = $userRecord['app_url'];

} catch (PDOException $e) {
    
    $api_url = "https://member.webbypage.com?no_url";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grant Access</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .grant-button {
            padding: 15px 30px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.2s;
        }
        .grant-button:hover {
            background-color: #0056b3;
        }
        .grant-button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <button class="grant-button" onclick="grantAccess()">Allow Grant Access</button>

    <script>
        function grantAccess() {
            // Redirect to the URL specified in $api_url
            window.location.href = "<?php echo $api_url; ?>";
        }
    </script>
</body>
</html>