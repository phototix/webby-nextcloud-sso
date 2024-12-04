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

        createNewUser($username, $email);

        // Log the user in
        $_SESSION['user'] = $username;
        exit;

    } catch (RequestException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

/**
 * Save user data to the database.
 *
 * @param string $userId
 * @param string|null $userEmail
 */
function createNewUser($userId, $userEmail)
{
    echo "Account details successfully taken: ".$userId." & email: ".$userEmail;
}
