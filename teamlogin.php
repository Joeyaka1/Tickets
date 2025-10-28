<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $wachtwoord = $_POST["wachtwoord"];
    $login_type = $_POST["login_type"]; // 'student' of 'docent'

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($wachtwoord, $user["wachtwoord"])) {
        // Controleer of de gebruiker overeenkomt met gekozen type
        // Stel: rol 0 = student, rol 1 = docent
        if (($login_type == 'student' && $user['rol'] == 0) || 
            ($login_type == 'docent' && $user['rol'] == 1)) {

            $_SESSION["iduser"] = $user["iduser"];
            $_SESSION["naam"] = $user["naam"];
            $_SESSION["rol"] = $user["rol"];

            // Doorsturen naar juiste dashboard
            if ($user["rol"] == 1) {
                header("Location: dashboard_docent.php");
            } else {
                header("Location: dashboard_student.php");
            }
            exit;
        } else {
            $error = "Je hebt op de verkeerde login-knop geklikt.";
        }
    } else {
        $error = "Ongeldige inloggegevens.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Inloggen</title>
<style>
    body { font-family: Arial, sans-serif; background: #f2f2f2; display: flex; justify-content: center; align-items: center; height: 100vh; }
    form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
    input { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
    button { width: 48%; padding: 10px; border: none; border-radius: 5px; color: white; cursor: pointer; }
    .student { background-color: #2196F3; }
    .docent { background-color: #4CAF50; }
    .buttons { display: flex; justify-content: space-between; margin-top: 10px; }
    .error { color: red; text-align: center; }
</style>
</head>
<body>
<form method="post">
    <h2>Inloggen</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
    <div class="buttons">
        <button type="submit" name="login_type" value="student" class="student">Student</button>
        <button type="submit" name="login_type" value="docent" class="docent">Docent</button>
    </div>
</form>
</body>
</html>
