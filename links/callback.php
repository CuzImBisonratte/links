<?php

// Include config file
$CONFIG = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Check if code is set
if (!isset($_GET['code'])) {
    // Redirect to login page
    header("Location: "
        . $CONFIG['oauth2']['cloudUrl'] . '/apps/oauth2/authorize'
        . "?client_id=" . $CONFIG['oauth2']['clientId']
        . "&redirect_uri=" . urlencode($CONFIG['oauth2']['redirectUri'])
        . "&response_type=code");
    exit();
}

// Exchange code for token
$URL = $CONFIG['oauth2']['cloudUrl'] . '/apps/oauth2/api/v1/token';
$URL .= "?grant_type=authorization_code";
$URL .= "&code=" . urlencode($_GET['code']);
$URL .= "&redirect_uri=" . urlencode($CONFIG['oauth2']['redirectUri']);
$AUTH = base64_encode($CONFIG['oauth2']['clientId'] . ":" . $CONFIG['oauth2']['clientSecret']);
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => [
        "Accept: */*",
        "Authorization: Basic " . $AUTH,
    ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) die();

// Decode response
$response = json_decode($response, true);

// Get user information
$URL = $CONFIG['oauth2']['cloudUrl'] . '/ocs/v1.php/cloud/user?format=json';
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $URL,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Accept: */*",
        "Authorization: Bearer " . $response['access_token'],
    ],
]);
$response = curl_exec($curl);
if (curl_error($curl)) die();
curl_close($curl);

// Decode response
$response = json_decode($response, true);

// Check if login was successful
if (!isset($response['ocs']['meta']['status']) || $response['ocs']['meta']['status'] != 'ok') {
    // Redirect to login page
    die("There was an error logging in. Please try again LATER.");
}

// Set session
session_start();
$_SESSION['user'] = $response;

// Redirect to link management portal
header("Location: /links/");
exit();
