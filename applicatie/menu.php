<?php

require_once('db_connectie.php');

// Maak verbinding met de database
$db = maakVerbinding();

// Haal alle producten op
$query = 'SELECT * FROM Product';
$stmt = $db->query($query);

// Controleer of er resultaten zijn
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten Overzicht</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            gap: 20px;
        }

        .product {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 15px;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .product h2 {
            margin: 10px 0;
            color: #333;
        }

        .product p {
            margin: 5px 0;
            color: #555;
        }

        .product p.price {
            font-weight: bold;
            color: #e63946;
        }
    </style>
</head>

<body>
    <h1>Onze Producten</h1>
    <div class="container">
        <?php
        if (count($producten) > 0) {
            // Doorloop elke rij en toon de gegevens
            foreach ($producten as $row) {
                echo "<div class='product'>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo "<p class='price'>Prijs: €" . htmlspecialchars($row['price']) . "</p>";
                echo "<p>Categorie: " . htmlspecialchars($row['type_id']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Geen producten gevonden.</p>";
        }
        ?>
    </div>
</body>

</html>
