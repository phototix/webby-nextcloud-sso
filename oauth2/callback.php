<?php
// Include the required dependencies
require_once '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

session_start();

// Load configuration
$config = require '../config.php';
$clientId = $config['client_id'];
$clientSecret = $config['client_secret'];

// Configuration
$nextcloudUrl = 'https://cloud.webbypage.com';
$redirectUri = 'https://member.webbypage.com/oauth2/callback.php';
$authorizationUrl = $nextcloudUrl . '/index.php/apps/oauth2/authorize';
$tokenUrl = $nextcloudUrl . '/index.php/apps/oauth2/api/v1/token';
$userInfoUrl = $nextcloudUrl . '/ocs/v1.php/cloud/user?format=json';

// Start the callback handler
if (isset($_GET['code'])) {
    if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
        exit('Invalid state:'.$_GET['state']." & session: ".$_SESSION['oauth2state']);
    }

    try {
        // Exchange authorization code for an access token
        $client = new Client();
        $response = $client->post($tokenUrl, [
            'form_params' => [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code',
                'code'          => $_GET['code'],
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);
        $accessToken = $tokenData['access_token'];

        // Step 3: Get User Info
        $response = $client->get($userInfoUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        $userData = json_decode($response->getBody(), true);
        $username = $userData['ocs']['data']['id'];
        $email = $userData['ocs']['data']['email'];

        // Handle user in database
        $userRedirectUrl = handleUserInDatabase($username, $email);

        if ($userRedirectUrl) {
            header("Location: $userRedirectUrl");
            exit;
        }

        // Log the user in
        $_SESSION['user'] = $username;
        exit;

    } catch (RequestException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

/**
 * Handle user creation or retrieval from database.
 *
 * @param string $userId
 * @param string|null $userEmail
 * @return string|null URL to redirect to if found
 */
function handleUserInDatabase($userId, $userEmail)
{
    // Include the database connection
    include('conn.php'); // This assumes you have a conn.php file for DB connection

    try {
        // Check if the email exists in the db_admin table
        $stmt = $pdo->prepare("SELECT * FROM db_admin WHERE admin_email = :admin_email");
        $stmt->execute(['admin_email' => $userEmail]);
        $userRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRecord) {
            // Record found, redirect to the URL stored in admin_unique column
            return $userRecord['admin_unique'];
        } else {
            // No record found, create a new one
            $stmt = $pdo->prepare("INSERT INTO db_admin (admin_name, admin_email, admin_unique) VALUES (:admin_name, :admin_email, 'https://member.webbypage.com')");
            $stmt->execute([
                'admin_name' => $userId,
                'admin_email' => $userEmail,
            ]);
            // You can return a URL if needed, or just return null for a general case
            return null;
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        return null;
    }
}