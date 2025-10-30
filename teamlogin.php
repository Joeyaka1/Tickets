<?php
session_start();
require 'db_con.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $wachtwoord = trim($_POST["wachtwoord"]);
    $rol = $_POST["rol"]; // 0 = student, 1 = docent

    // Controleer of gebruiker bestaat
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ? AND rol = ?");
    $stmt->bind_param("si", $email, $rol);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Controleer wachtwoord
        if (password_verify($wachtwoord, $user["wachtwoord"])) {
            $_SESSION["user_id"] = $user["iduser"];
            $_SESSION["rol"] = $user["rol"];

            if ($user["rol"] == 1) {
                header("Location: docent.php");
            } else {
                header("Location: planningbord.php");
            }
            exit;
        } else {
            $msg = "Onjuist wachtwoord.";
        }
    } else {
        $msg = "Gebruiker niet gevonden of rol klopt niet.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Inloggen</title>
<style>
body { font-family: Arial; background: #f7f7f7; display: flex; justify-content: center; align-items: center; height: 100vh; }
form { background: white; padding: 30px; border-radius: 10px; width: 300px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
button { background: #4CAF50; color: white; border: none; padding: 10px; border-radius: 5px; width: 100%; cursor: pointer; }
button:hover { background: #45a049; }
p { text-align: center; color: red; }
.role-buttons { display: flex; justify-content: space-between; margin-bottom: 10px; }
.role-buttons button { width: 48%; background: #2196F3; }
.role-buttons button:hover { background: #0b7dda; }
</style>
</head>
<body>

<form method="post">
    <h2>Team Login</h2>
    <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
    
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>

    <div class="role-buttons">
        <button type="submit" name="rol" value="0">Inloggen als Student</button>
        <button type="submit" name="rol" value="1">Inloggen als Docent</button>
    </div>
</form>

</body>
</html>
