<?php 
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // Gebruiker zoeken op naam of e-mail
    $stmt = $conn->prepare("SELECT * FROM user WHERE naam = ? OR email = ?");
    $stmt->bind_param("ss", $gebruikersnaam, $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Controleer wachtwoord (je kunt password_hash gebruiken bij registratie)
        if (password_verify($wachtwoord, $row['wachtwoord'])) {
            $_SESSION['iduser'] = $row['iduser'];
            $_SESSION['naam'] = $row['naam'];
            $_SESSION['rol'] = $row['rol'];

            // Doorsturen op basis van rol
            if ($row['rol'] == 1) {
                header("Location: docent.php");
            } else {
                header("Location: student.php");
            }
            exit;
        } else {
            $error = "Onjuist wachtwoord.";
        }
    } else {
        $error = "Gebruiker niet gevonden.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .student-button {
            background-color: #007bff;
            color: white;
        }

        .student-button:hover {
            background-color: #0056b3;
        }

        .teacher-button {
            background-color: #f0f0f0;
            color: #333;
        }

        .teacher-button:hover {
            background-color: #d9d9d9;
        }

    </style>
</head>
<body>

    <div class="login-container">
        <h2>Welkom</h2>
        <p>Log in als:</p>
        <a href="student.php"> <button class="button student-button">Student</button> </a>
        <a href="docent.php"> <button class="button teacher-button">Docent</button> </a>
    </div>

</body>
</html>
