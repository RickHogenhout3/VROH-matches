<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vroh-matches";
$charset = "utf8mb4";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>