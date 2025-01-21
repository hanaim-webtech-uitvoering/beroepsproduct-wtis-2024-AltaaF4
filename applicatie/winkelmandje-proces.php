<?php
session_start();

// Controleer of product_id aanwezig is in de POST-gegevens
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Haal het product op uit de database
    require_once('db_connectie.php');
    $db = maakVerbinding();
    $query = "SELECT * FROM Product WHERE type_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Voeg het product toe aan het winkelmandje (sessie)
    if ($product) {
        // Als het product al in het winkelmandje zit, verhoog de hoeveelheid
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    // Stuur de gebruiker terug naar de menupagina
    header("Location: menu.html");
    exit;
}
?>
