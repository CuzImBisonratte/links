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

// Start database connection
$DBC = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['username'], $CONFIG['db']['password'], $CONFIG['db']['dbname']);
if (!$DBC) die("Connection failed: " . mysqli_connect_error());

// Get links
$links = array();
$q = mysqli_prepare($DBC, "SELECT id, redirect FROM links WHERE user = ?");
$q->bind_param("s", $_SESSION['user']['ocs']['data']['id']);
$q->execute();
$q->bind_result($id, $redirect);
while ($q->fetch()) $links[] = array('id' => $id, 'redirect' => $redirect);
$q->close();

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
        <table>
            <tr>
                <th>Kurzlink</th>
                <th>Weiterleitung</th>
                <th>Aktionen</th>
            </tr>
            <?php

            for ($i = 0; $i < count($links); $i++) {
                echo "<tr>";
                echo "<td>" . $links[$i]['id'] . "</td>";
                echo "<td>" . $links[$i]['redirect'] . "</td>";
                echo "<td>";
                echo "<a href='delete.php?id=" . $links[$i]['id'] . "'>Löschen</a>";
                echo "</td>";
                echo "</tr>";
            }

            ?>
        </table>
    </main>
    <?php if (isset($CONFIG['footerMsg']) && $CONFIG['footerMsg'] != '') echo '<footer>' . $CONFIG['footerMsg'] . '</footer>'; ?>
</body>

</html>