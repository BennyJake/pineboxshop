<?php

namespace Test;

use Psecio\Vaultlib\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("vendor/autoload.php");

//var_dump($_SERVER);

// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "google.com");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

echo $output;

// close curl resource to free up system resources
curl_close($ch);

/*$accessToken = getenv('VAULT_WEB_TOKEN');
var_dump($accessToken);
$baseUrl = 'https://bennyjake.com:8200';

$client = new Client($accessToken, $baseUrl);

//$client = new \Psecio\Vaultlib\Client($accessToken, $baseUrl);

// If the vault is sealed, unseal it
if ($client->isSealed() == true) {
    $client->unseal($_ENV['VAULT_KEY_1']);
    $client->unseal($_ENV['VAULT_KEY_2']);
    $client->unseal($_ENV['VAULT_KEY_3']);
}
//var_dump($client->getList('pineboxshop'));
$result = $client->getSecret('pineboxshop');


var_dump($result);*/