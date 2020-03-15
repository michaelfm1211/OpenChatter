<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(GCP_OAUTH2_CLIENT_ID_SECRET);
$client->addScope('https://www.googleapis.com/auth/userinfo.profile');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$client->setAccessToken($_SESSION['access_token']);
	$oauth2 = new \Google_Service_Oauth2($client);
	$_SESSION['user_info'] = $oauth2->userinfo->get();
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
} else {
 	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
 	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
