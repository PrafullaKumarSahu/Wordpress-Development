<?php
require_once 'google-api-php-client/src/Google/autoload.php';

// Start a session to persist credentials.
session_start();

// Create the client object and set the authorization configuration
// from the client_secrets.json you downloaded from the Developers Console.
$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');

//$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/amrutkalash/wp-content/plugins/analytica/oauth2callback.php');
//$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/amrutkalash/wp-admin/admin.php?page=analytica-admin-settings');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
$client->setAccessType('offline');
$client->revokeToken();
// Handle authorization flow from the server.
if (! isset($_GET['code'])) {
 
  //$auth_url = $client->createAuthUrl();
  //header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
  //echo $auth_url;
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  var_dump($_SESSION['access_token']);exit;
 //$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/amrutkalash/wp-admin/admin.php?page=analytica-admin-settings';
 // header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
