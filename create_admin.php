<?php
require 'db_con.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = trim($_POST["naam"]);
    $email = trim($_POST["email"]);
    $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT);
    $rol = 1; // 1 = docent/admin
    $idklas = null; // docent heeft geen klas

    // Controleer of e-mail al bestaat
    $check = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    if ($check) {
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $msg = "E-mail bestaat al!";
        } else {
            // Voeg docent/admin toe
            $stmt = $mysqli->prepare("INSERT INTO user (naam, email, wachtwoord, rol, idklas) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                // Gebruik 'i' voor integer, 's' voor string
                $stmt->bind_param("sssii", $naam, $email, $wachtwoord, $rol, $idklas);
                $stmt->execute();
                $msg = "Docent/Admin-account succesvol aangemaakt!";
                $stmt->close();
            } else {
                $msg = "Fout bij het voorbereiden van INSERT-query: " . $mysqli->error;
            }
        }

        $check->close();
    } else {
        $msg = "Fout bij het voorbereiden van SELECT-query: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Maak Docent/Admin</title>
<style>
body { font-family: Arial; background: #f7f7f7; display: flex; justify-content: center; align-items: center; height: 100vh; }
form { background: white; padding: 30px; border-radius: 10px; width: 300px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
button { background: #4CAF50; color: white; border: none; padding: 10px; border-radius: 5px; width: 100%; }
p { text-align: center; color: green; }
</style>
</head>
<body>
<form method="post">
    <h2>Docent/Admin aanmaken</h2>
    <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
    <input type="text" name="naam" placeholder="Naam" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
    <button type="submit">Aanmaken</button>
</form>
</body>
</html>
