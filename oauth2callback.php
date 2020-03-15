<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

session_start();

$client = new Google_Client();
$client->setAuthConfigFile(GCP_OAUTH2_CLIENT_ID_SECRET);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->addScope('https://www.googleapis.com/auth/userinfo.profile');

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/login.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}