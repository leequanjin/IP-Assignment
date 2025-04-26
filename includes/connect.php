<?php

$host = 'localhost';
$dbName = 'huphwa';
$dbuser = 'user';
$dbpassword = 'test123';
$dsn = "mysql:host=$host;dbname=$dbName";

try {
    $conn = new PDO($dsn, $dbuser, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected Successfully";
} catch (Exception $ex) {
    echo "<p>ERROR: " . $ex->getMessage() . "</p>";
    exit;
}