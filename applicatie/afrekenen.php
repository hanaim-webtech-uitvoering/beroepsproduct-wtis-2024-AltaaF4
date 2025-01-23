<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd en of er een winkelwagen is
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Client') {
    header("Location: index.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    die("Uw winkelwagen is leeg. Voeg producten toe voordat u afrekent.");
}

// Haal de gebruiker op
$username = $_SESSION['username'];

// Bereken totaalprijs
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Verwerk het formulier voor het afronden van de bestelling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address']);
    
    if (empty($address)) {
        $error = "Vul alle verplichte velden in.";
    } else {
        $db = maakVerbinding();

        // Begin een transactie om consistentie te garanderen
        $db->beginTransaction();

        try {
            // Voeg de bestelling toe aan de Pizza_Order-tabel
            $orderQuery = "
                INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status, address) 
                VALUES (?, ?, ?, GETDATE(), ?, ?)
            ";
            $orderStmt = $db->prepare($orderQuery);
            $orderStmt->execute([
                $username,        // client_username
                $username,        // client_name
                'omer_personeel', // personnel_username
                1,               // status
                $address          // address
            ]);

            // Haal het ID van de nieuw toegevoegde bestelling op
            $order_id = $db->lastInsertId();

            // Voeg producten toe aan de Pizza_Order_Product-tabel
            $productQuery = "
                INSERT INTO Pizza_Order_Product (order_id, product_name, quantity) 
                VALUES (?, ?, ?)
            ";
            $productStmt = $db->prepare($productQuery);

            foreach ($_SESSION['cart'] as $item) {
                $productStmt->execute([
                    $order_id,          // order_id
                    $item['name'],      // product_name
                    $item['quantity']   // quantity
                ]);
            }

            // Bevestig de transactie
            $db->commit();

            // Leeg de winkelwagen
            unset($_SESSION['cart']);

            // Stuur de klant naar de bevestigingspagina
            header("Location: status.php");
            exit;
        } catch (Exception $e) {
            // Bij een fout: maak de transactie ongedaan
            $db->rollBack();
            die("Er is een fout opgetreden bij het verwerken van uw bestelling: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afrekenen</title>
</head>
<body>
    <h1>Afrekenen</h1>

    <!-- Toon de winkelwagen -->
    <table>
        <thead>
            <tr>
                <th>Product Naam</th>
                <th>Prijs (€)</th>
                <th>Aantal</th>
                <th>Subtotaal (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Totaal:</strong></td>
                <td><strong><?= number_format($total_price, 2, ',', '.') ?> €</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Formulier voor adres en bestelling afronden -->
    <div class="form-section">
        <form method="POST">
            <label for="address">Verzendadres:</label><br>
            <input type="text" id="address" name="address" placeholder="Uw adres" required><br>
            
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            
            <button type="submit">Bestelling Plaatsen</button>
        </form>
    </div>
</body>
</html>
