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

// Start database connection
$DBC = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['username'], $CONFIG['db']['password'], $CONFIG['db']['dbname']);
if (!$DBC) die("Connection failed: " . mysqli_connect_error());

// Set connection to use UTF-8
mysqli_set_charset($DBC, "utf8");

// Check if new link should be registered
if (isset($_POST['redirection']) && isset($_POST['shortlink'])) {
    $redirection = $_POST['redirection'];
    $shortlink = $_POST['shortlink'];

    // Register new link
    $q = mysqli_prepare($DBC, "INSERT INTO links (redirect, id, user) VALUES (?, ?, ?)");
    $q->bind_param("sss", $redirection, $shortlink, $_SESSION['user']['ocs']['data']['id']);
    $q->execute();
    $q->close();

    // Redirect back
    header("Location: /links/");
}

// Get links
$links = array();
$q = mysqli_prepare($DBC, "SELECT id FROM links");
$q->execute();
$q->bind_result($id);
while ($q->fetch()) $links[] = $id;
$q->close();

// Default shortlink $CONFIG['shortLinks']['default'] is length a-z and 0-9
$shortlink = "";
$shortlink_chars = "abcdefghijklmnpqrstuvwxyz123456789";
for ($i = 0; $i < $CONFIG['shortLinks']['default']; $i++) $shortlink .= $shortlink_chars[rand(0, strlen($shortlink_chars) - 1)];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link-Übersicht</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <a href="/links/addLink.php">+</a>
    </nav>
    <main>
        <form method="post" action="/links/addLink.php">
            <h1>Neuen Kurzlink anlegen</h1>
            <div>
                <div class="input-container">
                    <input type="url" autocomplete="url" spellcheck="false" tabindex="0" aria-label="Weiterleitungs-URL" name="redirection" value="" autocapitalize="none" placeholder="" required onfocus="this.placeholder='https://beispiel.de'" onblur="this.placeholder=''">
                    <div class="input-tooltip" aria-hidden="true">Weiterleitung</div>
                </div>
                <div class="input-container">
                    <input type="text" autocomplete="off" spellcheck="false" tabindex="0" aria-label="Kurzlink" name="shortlink" value="<?= $shortlink ?>" autocapitalize="none" placeholder="" required minlength="<?= $CONFIG['shortLinks']['min'] ?>" maxlength="<?= $CONFIG['shortLinks']['max'] ?>">
                    <div class="input-tooltip" aria-hidden="true">Kurzlink</div>
                </div>
            </div>
            <input type="submit" value="Kurz-Link anlegen">
        </form>
    </main>
    <?php if (isset($CONFIG['footerMsg']) && $CONFIG['footerMsg'] != '') echo '<footer>' . $CONFIG['footerMsg'] . '</footer>'; ?>
</body>

</html>