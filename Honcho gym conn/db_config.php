<?php
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "honchosgym";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone for Nigeria/Lagos (or your local time)
date_default_timezone_set('Africa/Lagos');
?>