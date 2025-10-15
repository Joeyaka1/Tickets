<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_style.css">
    <title>Document</title>
</head>
<body>

<form method="POST">

    <h1>Inloggen</h1>
    
        <h2>Welkom Student</h2>

  <label for="gebruikersnaam">Gebruikersnaam</label>
  <input type="text" id="gebruikersnaam" name="gebruikersnaam" required>

  <label for="wachtwoord">Wachtwoord</label>
  <input type="password" id="wachtwoord" name="wachtwoord" required>

  <a style="color:blue;" href="http://localhost/aanmelden.php">Ik heb nog geen account</a>

  <button type="submit" name="inloggen">Inloggen</button>
</form>
    
</body>
</html>