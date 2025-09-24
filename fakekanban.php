<?php include 'database.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="agenda.css">
    <title>Document</title>
</head>
<body>

    <nav class="navbar">
    <div class="nav-title">Fake-Kanban</div>
    <ul>
        <li><a href="planningbord.php">📅 Planning</a></li>
        <li><a href="profiel.php">👤 Profiel</a></li>
        <li><a href="loguit.php">Log uit</a></li>
    </ul>
    </nav>

</div>
    
    <div id='kanban'>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Opdracht</th>
                <th>In progressie</th>
                <th>Klaar</th>
                <th>Beoordeeld</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><button>Add</button></td>
                <td><button>Add</button></td>
                <td><button>Add</button></td>
                <td><button>Add</button></td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>