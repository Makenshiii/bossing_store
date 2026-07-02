<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "bossing_store";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

date_default_timezone_set("Asia/Manila");

?>