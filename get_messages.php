<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';
session_start(); 

if(ENABLE_API == true){
	header("Access-Control-Allow-Origin: *");
} else {
	header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_HOST']);
}

$chatfile = fopen(CHATFILE, "r");

echo fread($chatfile, filesize(CHATFILE));

fclose($chatfile);