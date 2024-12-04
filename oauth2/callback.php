<?php
// Include the required dependencies
require_once 'vendor/autoload.php';

// Configuration variables
$config = include('config.php'); // Load clientId and clientSecret from a separate config file
$clientId = $config['clientId'];
$clientSecret = $config['clientSecret'];
$redirectUri = 'https://member.webbypage.com/oauth2/callback.php';
$tokenEndpoint = 'https://cloud.webbypage.com/index.php/apps/oauth2/api/v1/token';
$userInfoEndpoint = 'https://cloud.webbypage.com/ocs/v2.php/cloud/user?format=json';

// Start the callback handler
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['code'])) {
    $authCode = $_GET['code'];

    try {
        // Exchange the authorization code for an access token
        $client = new \GuzzleHttp\Client();
        $response = $client->post($tokenEndpoint, [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
                'code' => $authCode,
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);
        if (isset($tokenData['access_token'])) {
            $accessToken = $tokenData['access_token'];

            // Fetch user information using the access token
            $userResponse = $client->get($userInfoEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            $userData = json_decode($userResponse->getBody(), true);
            if (isset($userData['ocs']['data']['id'])) {
                // Handle the user data (store in database, etc.)
                $userId = $userData['ocs']['data']['id'];
                $userEmail = $userData['ocs']['data']['email'] ?? null;

                // Store user data in database (example logic)
                saveUser($userId, $userEmail);

                // Redirect or display success message
                echo "Login successful! User ID: $userId, Email: $userEmail";
            } else {
                throw new Exception('Failed to retrieve user data.');
            }
        } else {
            throw new Exception('Failed to retrieve access token.');
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request.';
}

/**
 * Save user data to the database.
 *
 * @param string $userId
 * @param string|null $userEmail
 */
function saveUser($userId, $userEmail)
{
    echo "Account details successfully taken: ".$userId." & email: ".$userEmail;
}
