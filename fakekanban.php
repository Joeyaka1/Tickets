<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];

include 'database.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $bereikt = $_POST['bereikt'];
    $doel = $_POST['doel'];
    $drempel = $_POST['drempel'];
    $vak = $_POST['vak'];

    
    $stmt = $conn->prepare("INSERT INTO Taak (iduser, idvak, bereikt, doel, drempel) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $loggedInUserId, $vak, $bereikt, $doel, $drempel);
    $stmt->execute();

   


    echo '<div class="alert alert-success">
        taak is toegevoegd<br>
        </div>';
        echo "<script>
         setTimeout(function() {
            window.location.href = 'http://localhost/planningbord.php';
        }, 2000);
        </script>";
}


$stmt = $conn->prepare("SELECT naam FROM user WHERE iduser = ?");
$stmt->bind_param("i", $loggedInUserId);
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

<nav class="navbar">
    <div class="nav-title">📋 Real-Kanban</div>
    <ul>
        <li><a href="planningbord.php">📅 Planning</a></li>
        <li><a href="profiel.php">👤 Profiel</a></li>
        <li><a href="loguit.php">Log uit</a></li>
    </ul>
</nav>



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

    <button type="submit">➕ Taak toevoegen</button>
  </form>
</div>

</body>
</html>