<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "";
$database = "trgovina";

echo '<pre>Debug: Pokušavam spojiti se na bazu...</pre>';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Debug DB greška: " . $conn->connect_error);
}

echo '<pre>Debug: Uspješno spojen na bazu</pre>';
?>