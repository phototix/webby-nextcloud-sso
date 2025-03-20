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
$redirectUri = 'https://member.webbypage.com/oauth2/callback.php';
$authorizationUrl = $nextcloudUrl . '/apps/oauth2/authorize';
$tokenUrl = $nextcloudUrl . '/apps/oauth2/api/v1/token';
$userInfoUrl = $nextcloudUrl . '/ocs/v1.php/cloud/user?format=json';

if (!isset($_GET['code'])) {
    if (!empty($_SESSION['oauth2state'])) {
        include("login.php");
    } else {
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
} else {
    // Validate the state parameter
    if (empty($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    }

    // Exchange the authorization code for an access token
    $httpClient = new Client();
    try {
        $response = $httpClient->post($tokenUrl, [
            'form_params' => [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'code'          => $_GET['code'],
                'grant_type'    => 'authorization_code',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Check if a redirect URL is provided in the query parameters
        if (isset($_GET['redirect'])) {
            $redirectUrl = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
            if (filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
                // Append the token as a parameter to the redirect URL
                $redirectWithToken = $redirectUrl . (strpos($redirectUrl, '?') === false ? '?' : '&') . 'token=' . $data['access_token'];
                header('Location: ' . $redirectWithToken);
                exit;
            } else {
                exit('Invalid redirect URL');
            }
        } else {
            include("login.php");
        }
    } catch (RequestException $e) {
        exit('Error retrieving access token: ' . $e->getMessage());
    }
}
?>
