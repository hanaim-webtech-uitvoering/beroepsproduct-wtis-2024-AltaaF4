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
        // Wachtwoord klopt, start sessie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo "Welkom, " . htmlspecialchars($user['username']) . "! U bent succesvol ingelogd. U word naar de menu pagina gestuurd binnen 10 seconden.";
        header("Location: menu.html");
        exit;
    } else {
        // Ongeldig wachtwoord of gebruikersnaam
        echo "Ongeldige gebruikersnaam of wachtwoord.";
    }
}

//var_dump($username, $password);
?>
