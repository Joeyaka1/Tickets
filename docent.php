<?php
session_start();
require 'db_con.php';

// Controleer of docent is ingelogd
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
    header("Location: login.php");
    exit;
}

// Ophalen docentgegevens
$iduser = $_SESSION['user_id'];

// Student toevoegen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $naam = trim($_POST["naam"]);
    $email = trim($_POST["email"]);
    $wachtwoord = password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT);
    $rol = 0; // 0 = student
    $idklas = $_POST["idklas"];

    $stmt = $mysqli->prepare("INSERT INTO user (naam, email, wachtwoord, rol, idklas) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $naam, $email, $wachtwoord, $rol, $idklas);
    $stmt->execute();
    $msg = "Student succesvol toegevoegd!";
}

// Ophalen klassen
$klassen = $mysqli->query("SELECT * FROM klas");

// Ophalen alle studenten met hun klas
$studenten = $mysqli->query("SELECT u.iduser, u.naam, u.email, k.naam AS klasnaam 
                             FROM user u
                             LEFT JOIN klas k ON u.idklas = k.idklas
                             WHERE u.rol = 0
                             ORDER BY k.naam, u.naam");

?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Docent Dashboard</title>
<style>
body { font-family: Arial; background: #f4f6f9; margin: 0; padding: 0; }
.container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
h1 { text-align: center; color: #333; }
form { margin-bottom: 30px; }
input, select { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #45a049; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
th { background: #f2f2f2; }
.message { text-align: center; color: green; font-weight: bold; }
.logout { float: right; text-decoration: none; color: white; background: #e74c3c; padding: 8px 12px; border-radius: 5px; }
.logout:hover { background: #c0392b; }
.Taak_Toevoegen { float: right; text-decoration: none; color: white; background: #4CAF50; padding: 8px 12px; margin: 0 10px 0 0; border-radius: 5px; }
.Taak_Toevoegen:hover { background: #45a049; }
</style>
</head>
<body>
<div class="container">
    <a href="logout.php" class="logout">Log uit</a>
    <a href="fakekanban_docent.php" class="Taak_Toevoegen">Taak toevoegen</a>
    
    <h1>Docent Dashboard</h1>
    
    <?php if (!empty($msg)) echo "<p class='message'>$msg</p>"; ?>

    <h2>Nieuwe student toevoegen</h2>
    <form method="post">
        <input type="text" name="naam" placeholder="Naam" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
        <select name="idklas" required>
            <option value="">Selecteer een klas</option>
            <?php while ($row = $klassen->fetch_assoc()) { ?>
                <option value="<?= $row['idklas'] ?>"><?= htmlspecialchars($row['naam']) ?></option>
            <?php } ?>
        </select>
        <button type="submit" name="add_student">Student toevoegen</button>
    </form>

    <h2>Overzicht van studenten</h2>
    <table>
        <tr>
            <th>Naam</th>
            <th>E-mail</th>
            <th>Klas</th>
        </tr>
        <?php while ($row = $studenten->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['naam']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['klasnaam'] ?? 'Geen klas') ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
