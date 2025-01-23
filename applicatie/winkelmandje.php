<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Client') {
    header("Location: index.php");
    exit;
}

// Haal de winkelwagen op uit de sessie
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelwagen</title>
</head>
<body>
    <h1>Winkelwagen</h1>
    <table>
        <thead>
            <tr>
                <th>Product Naam</th>
                <th>Prijs (€)</th>
                <th>Aantal</th>
                <th>Totaal (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cart)): ?>
                <?php 
                $grandTotal = 0;
                foreach ($cart as $item): 
                    $total = $item['price'] * $item['quantity'];
                    $grandTotal += $total;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td><?= number_format($total, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Totaal</strong></td>
                    <td><strong><?= number_format($grandTotal, 2, ',', '.') ?></strong></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4">Je winkelwagen is leeg.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="actions">
        <a href="menu.php">Verder winkelen</a> | 
        <a href="afrekenen.php">Afrekenen</a>
    </div>
</body>
</html>
