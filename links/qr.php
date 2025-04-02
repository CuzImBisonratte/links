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

// Get link ID from URL
$link = $_GET['id'] ?? null;
$shortURL = $CONFIG['instanceUrl'] . ($CONFIG['shortLinks']['pretty'] ? "/" : "/?l=") . $link;

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
    <?php

    include 'qr_lib.php';

    $options = array(
        'w' => 800,
        'h' => 800,
    );

    $generator = new QRCode($shortURL, $options);

    $image = $generator->render_image();

    // Make a base64 string from the image
    ob_start();
    imagepng($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    $base64 = base64_encode($imageData);
    $src = 'data:image/png;base64,' . $base64;
    // Display the image
    echo "<img src='$src' alt='QR Code' />";

    imagedestroy($image);

    ?>
    <h1><?= $shortURL ?></h1>
    <style>
        body,
        html {
            margin: 0;
            inset: 0;
            overflow: hidden;
        }

        img {
            display: grid;
            margin: auto;
            aspect-ratio: 1/1;
            width: 90dvmin;
        }

        h1 {
            background-color: #232425;
            width: 100%;
            text-align: center;
            height: 10dvmin;
            position: absolute;
            bottom: 0;
            color: #ffffff;
            margin: 0;
            display: grid;
            place-content: center;
            font-size: <?php $linkLength = strlen($shortURL);
                        echo 9 - ($linkLength / 9) . 'dvw';
                        ?>;
            white-space: nowrap;
        }
    </style>
</body>

</html>