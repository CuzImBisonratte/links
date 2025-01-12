<?php

// Check if link is clicked
if (!isset($_GET['l'])) {
    // Redirect to link management portal
    header("Location: /links/");
    exit();
}

// Include config file
$CONFIG = require_once 'config.php';
$DBC = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['username'], $CONFIG['db']['password'], $CONFIG['db']['dbname']);
if (!$DBC) die("Connection failed: " . mysqli_connect_error());

$l = $_GET['l'];
var_dump($l);

// Get link
$q = mysqli_prepare($DBC, "SELECT redirect FROM links WHERE id = ?");
$q->bind_param("s", $l);
$q->execute();
$q->store_result();
if ($q->num_rows == 0) {
    // Redirect to link management portal
    header("Location: /error.php?e=404");
    exit();
}
$q->bind_result($link);
$q->fetch();
$q->close();

// Redirect to link
header("Location: $link");
exit();
