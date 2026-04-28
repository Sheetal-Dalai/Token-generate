<?php 

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "hms_db";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Optional: set charset
$connection->set_charset("utf8");

?>