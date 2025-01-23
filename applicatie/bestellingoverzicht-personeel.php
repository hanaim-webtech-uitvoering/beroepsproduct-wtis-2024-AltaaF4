<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd en de juiste rol heeft
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Personnel') {
    die('U heeft geen toegang tot deze pagina.');
}

// Maak verbinding met de database
$db = maakVerbinding();

// Verwerk de statusupdate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = intval($_POST['status']);

    // Update de status van de bestelling in de database
    $updateQuery = "UPDATE Pizza_Order SET status = ? WHERE order_id = ?";
    $stmt = $db->prepare($updateQuery);
    $stmt->execute([$new_status, $order_id]);

    // Geef een bericht weer dat de status is bijgewerkt
    $message = "Status van bestelling is succesvol bijgewerkt!";
}

// Haal alle bestellingen op, inclusief statusnamen
$query = "
    SELECT 
        po.order_id,
        po.client_name,
        po.personnel_username,
        po.datetime,
        po.status,
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

    <?php if (isset($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Klant Naam</th>
                <th>Personeel Gebruikersnaam</th>
                <th>Datum/Tijd</th>
                <th>Status</th>
                <th>Adres</th>
                <th>Wijzig Status</th>
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
                        <td>
                            <form action="bestellingoverzicht-personeel.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                <select name="status">
                                    <option value="1" <?= $order['status'] == 1 ? 'selected' : '' ?>>In behandeling</option>
                                    <option value="2" <?= $order['status'] == 2 ? 'selected' : '' ?>>Verzonden</option>
                                    <option value="3" <?= $order['status'] == 3 ? 'selected' : '' ?>>Afgeleverd</option>
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Geen bestellingen gevonden.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="actions">
        <a href="loguit.php">Uitloggen</a>
    </div>
</body>
</html>
