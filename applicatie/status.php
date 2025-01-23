<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd en de juiste rol heeft
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Client') {
    header("Location: index.php"); // Als de klant niet ingelogd is, stuur door naar de homepage
    exit;
}

$username = $_SESSION['username'];
$db = maakVerbinding();

// Haal bestellingen op voor de ingelogde klant
$query = "
    SELECT 
        po.order_id,
        po.datetime,
        po.status,
        po.address,
        CASE 
            WHEN po.status = 1 THEN 'In behandeling'
            WHEN po.status = 2 THEN 'Verzonden'
            WHEN po.status = 3 THEN 'Afgeleverd'
            ELSE 'Onbekend'
        END AS status_name
    FROM 
        Pizza_Order po
    WHERE 
        po.client_username = ?
    ORDER BY 
        po.datetime DESC
";

$stmt = $db->prepare($query);
$stmt->execute([$username]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Status</title>
</head>

<body>
    <h1>Mijn Bestelling Status</h1>

    <?php if (!empty($orders)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Datum/Tijd</th>
                    <th>Status</th>
                    <th>Adres</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?=htmlspecialchars($order['order_id']) ?></td>
                        <td><?= htmlspecialchars($order['datetime']) ?></td>
                        <td><?= htmlspecialchars($order['status_name']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er zijn geen bestellingen gevonden.</p>
    <?php endif; ?>

    <div class="actions">
        <a href="loguit.php">Uitloggen</a> |
        <a href="menu.php">Terug</a>
    </div>
</body>

</html>
