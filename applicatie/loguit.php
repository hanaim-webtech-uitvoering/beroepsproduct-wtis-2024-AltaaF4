<?php
session_unset();  // Verwijder alle sessievariabelen
session_destroy(); // Vernietig de sessie

header("Location: index.php");  // Redirect naar loginpagina
exit;
?>
