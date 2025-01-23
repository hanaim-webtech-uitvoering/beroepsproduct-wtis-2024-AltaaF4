<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Client') {
    header("Location: index.php");
    exit;
}

// Controleer of er productinformatie is ontvangen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'], $_POST['price'])) {
    $productName = $_POST['product_name'];
    $price = (float)$_POST['price'];

    // Controleer of de winkelwagen al bestaat
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Voeg het product toe aan de winkelwagen of update de hoeveelheid
    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $productName) {
            $item['quantity']++;
            $productExists = true;
            break;
        }
    }
    unset($item);

    // Als het product nog niet in de winkelwagen zit, voeg het toe
    if (!$productExists) {
        $_SESSION['cart'][] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => 1
        ];
    }

    // Redirect terug naar het menu
    header("Location: menu.php");
    exit;
}

// Als de pagina direct wordt benaderd zonder POST-gegevens
header("Location: menu.php");
exit;
