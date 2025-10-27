<?php
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
    $rol = $_POST['rol']; // 0 = student, 1 = docent (later omwisselen)
    $idklas = $_POST['idklas']; // optioneel, alleen bij studenten

    $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

    if ($rol == 1) {
        $stmt = $conn->prepare("INSERT INTO user (naam, email, wachtwoord, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $naam, $email, $hashedPassword, $rol);
    } else {
        $stmt = $conn->prepare("INSERT INTO user (naam, email, wachtwoord, rol, idklas) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $naam, $email, $hashedPassword, $rol, $idklas);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Account succesvol aangemaakt!'); window.location='login_pagina.php';</script>";
    } else {
        echo "<script>alert('Er is iets misgegaan bij het registreren.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanmelden</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<form method="POST" action="">
    <h2>Aanmelden</h2>

    <label for="naam">Naam</label>
    <input type="text" id="naam" name="naam" required>

    <label for="email">E-mailadres</label>
    <input type="email" id="email" name="email" required>

    <label for="wachtwoord">Wachtwoord</label>
    <input type="password" id="wachtwoord" name="wachtwoord" required>

    <label for="rol">Rol</label>
    <select name="rol" id="rol" required onchange="toggleKlas()">
        <option value="">Kies rol</option>
        <option value="0">Student</option>
        <option value="1">Docent</option>
    </select>

    <div id="klasContainer" style="display:none;">
        <label for="idklas">Klas</label>
        <input type="number" id="idklas" name="idklas" placeholder="Bijv. 1 of 2">
    </div>

    <button type="submit">Account aanmaken</button>
    <a href="login_pagina.php">Ik heb al een account</a>
</form>

</body>
</html>
