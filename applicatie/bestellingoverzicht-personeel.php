<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd en de juiste rol heeft
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Personnel') {
    die('U heeft geen toegang tot deze pagina.');
}

// Maak verbinding met de database
$db = maakVerbinding();

// Haal alle bestellingen op, inclusief statusnamen
$query = "
    SELECT 
        po.order_id,
        po.client_name,
        po.personnel_username,
        po.datetime,
        CASE 
            WHEN po.status = 1 THEN 'In behandeling'
            WHEN po.status = 2 THEN 'Verzonden'
            WHEN po.status = 3 THEN 'Afgeleverd'
            ELSE 'Onbekend'
        END AS status_name,
        po.address
    FROM 
        Pizza_Order po
    ORDER BY 
        po.datetime DESC
";

$stmt = $db->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Overzicht</title>
</head>
<body>
    <h1>Bestelling Overzicht</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Klant Naam</th>
                <th>Personeel Gebruikersnaam</th>
                <th>Datum/Tijd</th>
                <th>Status</th>
                <th>Adres</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <a href="detailoverzicht-bestelling.php?order_id=<?= urlencode($order['order_id']) ?>">
                                <?= htmlspecialchars($order['order_id']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($order['client_name']) ?></td>
                        <td><?= htmlspecialchars($order['personnel_username']) ?></td>
                        <td><?= htmlspecialchars($order['datetime']) ?></td>
                        <td><?= htmlspecialchars($order['status_name']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Geen bestellingen gevonden.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>