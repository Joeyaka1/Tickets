<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];

include 'database.php';

$query = "SELECT t.bereikt, t.doel, t.drempel, v.naam AS vaknaam
        FROM Taak t
        JOIN vak v ON t.idvak = v.idvak
        WHERE t.iduser = ?
        ORDER BY v.naam";

$stmt = $conn->prepare($query); 
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();

$taken = [];
while ($row = $result->fetch_assoc()) {
    $taken[] = $row;
}

$stmt->close();

$stmt = $conn->prepare("SELECT naam FROM user WHERE iduser = ?");
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$gebruikersnaam = $user['naam'] ?? 'Onbekende gebruiker';
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/agenda.css">
    <title>Document</title>
</head>
<body>

    <nav class="navbar">
    <div class="nav-title">Real-Kanban</div>
    <ul>
        <li><a href="fakekanban.php">🎟 Taak</a></li>
        <li><a href="profiel.php">👤 Profiel</a></li>
        <li><a href="loguit.php"> Log uit</a></li>
    </ul>
    </nav>

</div>

<h1 class="WelkomNaam">welkom <?= htmlspecialchars($gebruikersnaam) ?></h1>
    
   <div id='kanban'>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Opdracht (Vak)</th>
                    <th>In progressie (Bereikt)</th>
                    <th>Klaar (Doel)</th>
                    <th>Beoordeeld (Drempel)</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($taken)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #777;">Geen taken gevonden voor deze gebruiker.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($taken as $taak): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($taak['vaknaam']); ?></strong>
                        </td>
                        
                        <td>
                            <?= nl2br(htmlspecialchars($taak['bereikt'])); ?>
                        </td>
                        
                        <td>
                            <?= nl2br(htmlspecialchars($taak['doel'])); ?>
                        </td>
                        
                        <td>
                            <?= nl2br(htmlspecialchars($taak['drempel'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>