<?php
session_start();
require_once('db_connectie.php');

// Controleer of de gebruiker is ingelogd en de juiste rol heeft
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Personnel') {
    die('U heeft geen toegang tot deze pagina.');
}

// Controleer of order_id is opgegeven in de URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die('Geen bestelling geselecteerd.');
}

$order_id = intval($_GET['order_id']);
$db = maakVerbinding();

// Haal basisinformatie van de bestelling op
$orderQuery = "
    SELECT 
        po.order_id,
        po.client_name,
        po.datetime,
        po.status,
        po.address,
        po.personnel_username
    FROM 
        Pizza_Order po
    WHERE 
        po.order_id = ?
";

$orderStmt = $db->prepare($orderQuery);
$orderStmt->execute([$order_id]);
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Bestelling niet gevonden.');
}

// Haal producten en hun details op
$productQuery = "
    SELECT 
        pop.product_name,
        pop.quantity,
        p.price,
        p.type_id
    FROM 
        Pizza_Order_Product pop
    INNER JOIN 
        Product p
    ON 
        pop.product_name = p.name
    WHERE 
        pop.order_id = ?
";

$productStmt = $db->prepare($productQuery);
$productStmt->execute([$order_id]);
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

// Haal ingrediënten per product op
$ingredientsQuery = "
    SELECT 
        pi.product_name,
        pi.ingredient_name
    FROM 
        Product_Ingredient pi
    WHERE 
        pi.product_name IN (
            SELECT product_name 
            FROM Pizza_Order_Product 
            WHERE order_id = ?
        )
";

$ingredientStmt = $db->prepare($ingredientsQuery);
$ingredientStmt->execute([$order_id]);
$ingredients = $ingredientStmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailoverzicht Bestelling</title>
</head>

<body>
    <h1>Detailoverzicht Bestelling #
        <?= htmlspecialchars($order['order_id']) ?>
    </h1>
    <p><strong>Klant Naam:</strong>
        <?= htmlspecialchars($order['client_name']) ?>
    </p>
    <p><strong>Datum/Tijd:</strong>
        <?= htmlspecialchars($order['datetime']) ?>
    </p>
    <p><strong>Status:</strong>
        <?= htmlspecialchars($order['status']) ?>
    </p>
    <p><strong>Adres:</strong>
        <?= htmlspecialchars($order['address']) ?>
    </p>
    <p><strong>Personeel:</strong>
        <?= htmlspecialchars($order['personnel_username']) ?>
    </p>

    <h2>Producten</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Product Naam</th>
                <th>Aantal</th>
                <th>Prijs per Stuk</th>
                <th>Type</th>
                <th>Ingrediënten</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($product['product_name']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($product['quantity']) ?>
                </td>
                <td>€
                    <?= htmlspecialchars(number_format($product['price'], 2)) ?>
                </td>
                <td>
                    <?= htmlspecialchars($product['type_id']) ?>
                </td>
                <td>
                    <?php 
                            $productName = $product['product_name'];
                            if (isset($ingredients[$productName])) {
                                $ingredientList = array_column($ingredients[$productName], 'ingredient_name');
                                echo htmlspecialchars(implode(', ', $ingredientList));
                            } else {
                                echo 'Geen ingrediënten.';
                            }
                        ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>