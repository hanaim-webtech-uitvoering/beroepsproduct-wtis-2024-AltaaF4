<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $privacyverklaring = "privacy_statement_21-01-2025.txt";
    if(file_exists($privacyverklaring)) {
        echo nl2br(file_get_contents($privacyverklaring)); //nl2br voor het makkelijk en mooi laten zien van lege regels etc.
    }
    else 
        echo"kan privacyverklaring niet ophalen";
?>
</body>
</html>