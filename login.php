<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!session_id()) {
    session_start();
}

require_once("vendor/autoload.php");
$fb = new \Facebook\Facebook([
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v2.10',
    //'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://pinebox.shop/', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';