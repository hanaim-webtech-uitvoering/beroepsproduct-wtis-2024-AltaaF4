<?php
require_once('db_connectie.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;

    if (empty($username) || empty($password) || empty($first_name) || empty($last_name)) {
        die('Alle verplichte velden moeten worden ingevuld.');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $db = maakVerbinding();

    // IEDEREEN IS CLIENT, HET IS GEEN EIS OM ALS MEDEWERKER TE REGISTREREN!
    $role = 'Client';

    // username controle
    $checkQuery = "SELECT * FROM [User] WHERE username = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute([$username]);

    if ($checkStmt->fetchColumn() > 0) {
        die('Deze gebruikersnaam is al in gebruik. Kies een andere.');
    }

    $query = "INSERT INTO [User] (username, password, first_name, last_name, role, address) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
   
    //debugging van chatgpt
    try {
        $stmt->execute([$username, $hashed_password, $first_name, $last_name, $role, $address]);
        echo 'Registratie succesvol! U kunt nu inloggen. U word overgebracht naar de login pagina binnen 10 seconden.';
        header("Location: login.html");
    } catch (PDOException $e) {
        echo 'SQL-foutcode: ' . $e->getCode() . '<br>';
        echo 'Foutmelding: ' . $e->getMessage() . '<br>';
        die('Er is een fout opgetreden.');
    }
}
?>