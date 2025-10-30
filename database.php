<?php
$servername = "mysql";
$username = "root";
$password = "password";
$dbname = "planning";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>