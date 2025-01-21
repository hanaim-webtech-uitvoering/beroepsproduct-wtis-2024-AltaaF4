<?php
session_start();

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<h1>Winkelmandje</h1>";

    $totaal = 0;
    foreach ($_SESSION['cart'] as $product_id => $product) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
        echo "<p>Prijs: €" . htmlspecialchars($product['price']) . "</p>";
        echo "<p>Aantal: " . htmlspecialchars($product['quantity']) . "</p>";
        echo "<form action='update_cart.php' method='POST'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "' />";
        echo "<input type='number' name='quantity' value='" . $product['quantity'] . "' min='1' />";
        echo "<button type='submit'>Aantal bijwerken</button>";
        echo "</form>";

        echo "<form action='remove_from_cart.php' method='POST'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "' />";
        echo "<button type='submit'>Verwijder uit winkelmandje</button>";
        echo "</form>";

        $totaal += $product['price'] * $product['quantity'];
        echo "</div>";
    }

    echo "<h2>Totaal: €" . $totaal . "</h2>";
    echo "<a href='afrekenen.php'>Doorgaan naar afrekenen</a>";
} else {
    echo "<p>Je winkelmandje is leeg.</p>";
}

echo "<br><a href='menu.html'>Terug naar het menu</a>";
?>
