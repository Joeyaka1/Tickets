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

include 'database.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $bereikt = $_POST['bereikt'];
    $doel = $_POST['doel'];
    $drempel = $_POST['drempel'];
    $vak = $_POST['vak'];
    $deadline = $_POST['deadline'] ?? null;

    
    $stmt = $conn->prepare("INSERT INTO Taak (iduser, idvak, bereikt, doel, drempel, deadline) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $iduser, $vak, $bereikt, $doel, $drempel, $deadline);
    $stmt->execute();

   


    echo '<div class="alert alert-success">
        taak is toegevoegd<br>
        </div>';
        echo "<script>
         setTimeout(function() {
            window.location.href = 'http://localhost/docent.php';
        }, 2000);
        </script>";

       
}


$stmt = $conn->prepare("SELECT naam FROM user WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$gebruikersnaam = $user['naam'] ?? 'Onbekende gebruiker';
$stmt->close();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/agenda.css">
  <title>Taak toevoegen</title>
</head>
<body>


<div id="toevoegen">
  <h2>Nieuw Taak Toevoegen</h2>

  <form method="post">

  <label>Gebruiker:</label>
  <p><?= htmlspecialchars($gebruikersnaam) ?></p>
  

    <label>Vak:</label>
        <select name="vak" required>
            <?php
                $result = $conn->query("SELECT idvak, naam FROM vak ORDER BY naam");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['idvak']}'>{$row['naam']}</option>";
                            } ?>  
</select>

    <label>Wat heb je bereikt de afgelopen week(en):</label>
    <textarea name="bereikt" required></textarea><br>

    <label>Doel:</label>
    <textarea name="doel"></textarea><br>

    <label>Drempel:</label>
    <textarea name="drempel"></textarea><br>

    <label>Deadline:</label>
    <input type="date" name="deadline"><br>

    <button type="submit">➕ Taak toevoegen</button>
  </form>
</div>

</body>
</html>