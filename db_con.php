<?php
$server = "mysql";
$dbname = "planning";
$user = "root";
$pass = "password";

try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>
