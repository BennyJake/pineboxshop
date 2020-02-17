<?php
require_once("vendor/autoload.php");
$fb = new \Facebook\Facebook([
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v2.10',
    //'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost:8080/', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';