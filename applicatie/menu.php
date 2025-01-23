<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Client') {
    // Als de gebruiker niet is ingelogd als Client, stuur hem terug naar index.php
    header("Location: index.php");
    exit;
}

// Haal gebruikersnaam en rol op uit de sessie
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Maak verbinding met de database
$db = maakVerbinding();

// Haal alle producten op
$query = "SELECT name, price FROM Product ORDER BY name ASC";

$stmt = $db->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>

<body>
    <h1>Menu</h1>
    <div class="actions">
        <p>Welkom, <?= htmlspecialchars($role) . ' ' .  htmlspecialchars($username) ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product Naam</th>
                <th>Prijs (€)</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 2, ',', '.') ?></td>
                        <td>
                            <form method="POST" action="winkelmandje-proces.php">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
                                <button type="submit">Toevoegen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Geen producten beschikbaar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="actions">
        <a href="loguit.php">Uitloggen</a> |
        <a href="afrekenen.php">Afrekenen</a> |
        <a href="status.php">Status Bestelling(en)</a>
    </div>

</body>

</html>