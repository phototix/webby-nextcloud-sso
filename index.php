<?php
$showError = "yes";
if ($showError === "yes") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); // Use E_ALL for complete error reporting
}

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

// Handle redirect logic
if (isset($_GET['redirect'])) {
    $redirectUrl = filter_var($_GET['redirect'], FILTER_SANITIZE_URL); // Sanitize the redirect URL
    header('Location: ' . $redirectUrl);
    exit;
}

// OAuth2 authorization flow
if (!isset($_GET['code'])) {
    // Check session state or initialize a new one
    if (!empty($_SESSION['oauth2state']) && isset($_GET['state']) && $_GET['state'] === $_SESSION['oauth2state']) {
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
        unset($_SESSION['oauth2state']); // Prevent reuse
        die('Invalid state parameter.');
    }

    include("login.php");
}
?>
