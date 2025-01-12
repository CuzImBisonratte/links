<?php

// Include config file
$CONFIG = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to login page
    header("Location: "
        . $CONFIG['oauth2']['cloudUrl'] . '/apps/oauth2/authorize'
        . "?client_id=" . $CONFIG['oauth2']['clientId']
        . "&redirect_uri=" . urlencode($CONFIG['oauth2']['redirectUri'])
        . "&response_type=code");
    exit();
}
