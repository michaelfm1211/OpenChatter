<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';
session_start(); 

if(ENABLE_API == true){
	header("Access-Control-Allow-Origin: *");
} else {
	header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_HOST']);
}

$chatfile = fopen(CHATFILE, "a+");

if(empty($_REQUEST["msg"]) || empty($_REQUEST["redirect_uri"]) || !isset($_SESSION['user_info'])){
	$redirect_uri = $_REQUEST["redirect_uri"];
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}else{
	$user_info = $_SESSION["user_info"];
	$data = htmlspecialchars($user_info['given_name'] . " " . $user_info['family_name'][0] . ":" . $_REQUEST["msg"]);
	fwrite($chatfile, "_NXT_MSG_" . $data);
	echo fread($chatfile,filesize(CHATFILE));
}

fclose($chatfile);

$redirect_uri = $_REQUEST["redirect_uri"];
header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));