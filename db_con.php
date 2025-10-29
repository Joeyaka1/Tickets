<?php
$server = "mysql";
$dbname = "planning";
$user = "root";
$pass = "password";

$mysqli = new mysqli($server, $user, $pass, $dbname);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>
