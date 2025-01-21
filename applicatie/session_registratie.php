<?php
session_start();

if (isset($_SESSION['keren_bezocht'])) {
    $_SESSION['keren_bezocht']++;
} else {
    $_SESSION['keren_bezocht'] = 1;
}

print_r($_SESSION);

?>