<?php
if (isset($_GET['redirect'])) {
    $redirectUrl = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
    if (filter_var($redirectUrl, FILTER_VALIDATE_URL)) {
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        exit('Invalid redirect URL');
    }
} else {
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
    
        if(!empty($_SESSION['oauth2state'])){
            include("login.php");
        }else{
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
        include("login.php");
    }
}
?>
