<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "Shasha@23";
$dbName = "Charity";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>
