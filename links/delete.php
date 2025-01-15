<?php

// Include config file
$CONFIG = require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to login page
    header("Location: /links/");
    exit();
}

// Check if delete request is valid
if (!isset($_GET['id'])) {
    // Redirect to links page
    header("Location: /links/");
    exit();
}

// Start database connection
$DBC = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['username'], $CONFIG['db']['password'], $CONFIG['db']['dbname']);
if (!$DBC) die("Connection failed: " . mysqli_connect_error());

// Prepare delete statement
$stmt = mysqli_prepare($DBC, "DELETE FROM links WHERE id = ? AND user = ?");
$stmt->bind_param('ss', $_GET['id'], $_SESSION['user']['ocs']['data']['id']);
$stmt->execute();

// Redirect to links page
header("Location: /links/");
exit();
