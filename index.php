<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

session_start();

// Load configuration
$config = require 'config.php';
$clientId = $config['client_id'];
$clientSecret = $config['client_secret'];

// Configuration
$nextcloudUrl = 'https://cloud.webbypage.com/index.php';
$redirectUri = 'https://member.webbypage.com/oauth2/callback';
$authorizationUrl = $nextcloudUrl . '/apps/oauth2/authorize';
$tokenUrl = $nextcloudUrl . '/apps/oauth2/api/v1/token';
$userInfoUrl = $nextcloudUrl . '/ocs/v1.php/cloud/user?format=json';

// Step 1: Redirect to Nextcloud Authorization
if (!isset($_GET['code'])) {
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth2state'] = $state;

    $authUrl = $authorizationUrl . '?' . http_build_query([
        'client_id'     => $clientId,
        'redirect_uri'  => $redirectUri,
        'response_type' => 'code',
        'scope'         => '',
        'state'         => $state,
    ]);

    header('Location: ' . $authUrl);
    exit;
}

// Step 2: Handle Callback and Get Access Token
if (isset($_GET['code'])) {
    if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
        exit('Invalid state');
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

        // Step 4: Check User in Your App
        if (!checkIfUserExists($username)) {
            createNewUser($username, $email);
        }

        // Log the user in
        $_SESSION['user'] = $username;
        header('Location: /dashboard');
        exit;

    } catch (RequestException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Function to check if a user exists in your app
function checkIfUserExists($username) {
    // Implement database check logic
    return false; // Placeholder
}

// Function to create a new user
function createNewUser($username, $email) {
    // Implement database insert logic
}

?>
