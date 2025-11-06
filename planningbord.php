<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bereikt'])) {
    $taakId = $_POST['taak_id']; 
    $bereikt = $_POST['bereikt'] ?? 'Nee';

    
    $stmt = $conn->prepare("UPDATE Taak SET klaar = ? WHERE iduser = ? AND idtaak = ?");
    $stmt->bind_param("sii", $bereikt, $loggedInUserId, $taakId);
    $stmt->execute();
    $stmt->close();

  
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT t.idtaak, t.bereikt, t.klaar, t.doel, t.drempel, v.naam AS vaknaam
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

$query2 = "
SELECT 
    t.doel,
    t.drempel,
    t.bereikt,
    u.naam AS docent,
    v.naam AS vaknaam,
    t.deadline
FROM Taak t
JOIN user u ON t.iduser = u.iduser
JOIN vak v ON t.idvak = v.idvak
WHERE u.rol = 1;
";

$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$result2 = $stmt2->get_result();

$taken2 = [];
while ($row = $result2->fetch_assoc()) {
    $taken2[] = $row;
}

$stmt2->close();

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

<h2 style="text-align:center; margin-top:40px;">jouw toegevoegden taken</h2>
    
   <div id='kanban'>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Opdracht (Vak)</th>
                    <th>In progressie (Bereikt)</th>
                    <th>Doel</th>
                    <th>Beoordeeld (Drempel)</th>
                    <th>Klaar</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($taken)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #777;">Geen taken gevonden voor deze gebruiker.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($taken as $taak):?>
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

                        <td style="background-color: <?= $taak['klaar'] == 'Ja' ? '#d4edda' : '#f8d7da' ?>; text-align:center; font-weight:bold;">
                          <form method="post">
                          <input type="hidden" name="taak_id" value="<?= $taak['idtaak'] ?>">
                          <input type="hidden" name="update_bereikt" value="1">
                          <select name="bereikt">
                             <option value="Ja" <?= $taak['klaar'] == 'Ja' ? 'selected' : '' ?>>Ja</option>
                             <option value="Nee" <?= $taak['klaar'] == 'Nee' ? 'selected' : '' ?>>Nee</option>
                          </select>
                          <button type="submit" style="margin-left:5px;">Opslaan</button>
                          </form>
                       </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h2 style="text-align:center; margin-top:40px;">Docent Tabel</h2>

    <div id='kanban'>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
            <tr>
            <th>docent</th>
            <th>Opdracht (Vak)</th>
            <th>In progressie (Bereikt)</th>
            <th>Klaar (Doel)</th>
            <th>Beoordeeld (Drempel)</th>
            <th>deadline</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($taken)): ?>
            <tr>
              <td colspan="4" style="text-align: center; color: #777;">Geen taken gevonden voor deze gebruiker.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($taken2 as $taak): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($taak['docent']); ?></strong>
                        </td>
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

                           <td>
                            <?= nl2br(htmlspecialchars($taak['deadline'] ?? '')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>


</body>
</html>