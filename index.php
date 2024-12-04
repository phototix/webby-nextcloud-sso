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

// Step 1: Redirect to Nextcloud Authorization
if (!isset($_GET['code'])) {

    if(!isset($_SESSION['oauth2state']) && $_SESSION['oauth2state']==""){
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
        
}else{

    echo "Welcome to WebbyPage Member Connector Center";

}
?>
