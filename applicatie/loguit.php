<?php
session_unset();  // Verwijder alle sessievariabelen
session_destroy(); // Vernietig de sessie

header("Location: login.html");  // Redirect naar loginpagina
exit;
?>
