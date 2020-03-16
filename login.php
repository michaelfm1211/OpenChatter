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
	$user_info = $oauth2->userinfo->get();
	$_SESSION['user_info'] = $user_info;

	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/chat_message.php?msg=' . $user_info['given_name'] . "+" . $user_info['family_name'][0] . "+had+joined+the+chat.&redirect_uri=http%3A%2F%2Flocalhost%2F";	// Chat that the user has joined the chat, then redirect to /
	
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
} else {
 	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
 	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
