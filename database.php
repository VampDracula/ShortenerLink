<?php
$servername = "localhost";
$username = "Vamp";
$password = "Vamp6666";
$dbname = "myshortlink";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>