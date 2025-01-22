<?php
// Deze pagina is voor niet-ingelogde gebruikers of voor gebruikers die niet hun winkelmand kunnen beheren

// Maak verbinding met de database
require_once('db_connectie.php');
$db = maakVerbinding();

// Haal alle producten op
$query = "
    SELECT 
        name,
        price
    FROM 
        Product
    ORDER BY 
        name ASC
";

$stmt = $db->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Menu</h1>
    <p>Welkom! Bekijk de producten hieronder. Log in om bestellingen toe te voegen aan je winkelmand.</p>
    
    <table>
        <thead>
            <tr>
                <th>Product Naam</th>
                <th>Prijs (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Geen producten beschikbaar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <p><a href="login.php">Log in</a> of <a href="registreren.php">Registreer</a> om producten aan je winkelmand toe te voegen.</p>
</body>
</html>
