<?php
session_start();
require_once('db_connectie.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) or empty($password)) {
        die('Gebruikersnaam en wachtwoord zijn verplicht.');
    }

    $db = maakVerbinding();

    // Haal de gebruiker op uit de database
    $query = "SELECT * FROM [User] WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Controleer de rol van de gebruiker en stuur door naar de juiste pagina
        if ($user['role'] === 'Client') {
            header("Location: menu.php");
            exit; 
        } elseif ($user['role'] === 'Personnel') {
            header("Location: bestellingoverzicht-personeel.php");
            exit; 
        } else {
            die('Onbekende gebruikersrol.');
        }
        
        exit;
    } else {
        // Ongeldig wachtwoord of gebruikersnaam
        echo "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
</head>
<body>
    <h1>Login Pagina</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Gebruikersnaam" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <button type="submit">Inloggen</button>
    </form>
</body>
</html>
