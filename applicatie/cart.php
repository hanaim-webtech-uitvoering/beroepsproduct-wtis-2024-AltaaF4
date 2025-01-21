<?php
session_start();
require_once('db_connectie.php');

// Maak verbinding met de database
$db = maakVerbinding();

// Controleer of het winkelmandje niet leeg is
$cart = $_SESSION['cart'] ?? [];

if (!empty($cart)) {
    // Haal productdetails uit de database
    $ids = implode(',', array_keys($cart));
    $query = "SELECT * FROM Product WHERE id IN ($ids)";
    $stmt = $db->query($query);
    $producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $producten = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winkelmandje</title>
</head>

<body>
    <h1>Winkelmandje</h1>
    <?php if (!empty($producten)) : ?>
        <ul>
            <?php foreach ($producten as $product) : ?>
                <li>
                    <?= htmlspecialchars($product['name']) ?> - €<?= htmlspecialchars($product['price']) ?>
                    (<?= $cart[$product['id']] ?> stuks)
                    <form method="post" action="menu.php">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" name="remove_from_cart">Verwijderen</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Je winkelmandje is leeg.</p>
    <?php endif; ?>
</body>

</html>
