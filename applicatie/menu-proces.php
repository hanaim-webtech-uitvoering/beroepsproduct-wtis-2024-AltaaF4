<?php
require_once('db_connectie.php');

// Maak verbinding met de database
$db = maakVerbinding();

// Haal alle producten uit de database
$query = "SELECT * FROM Product";
$stmt = $db->query($query);
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Voeg de producten toe aan de sessie om in de HTML weer te geven
$_SESSION['producten'] = $producten;

?>