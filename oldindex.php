<?php
session_start();

if (!isset($_SESSION['token'])) {
    header("Location: ./frontend/home.php");
    exit();
} elseif ($_SESSION['user_type'] === 'admin') {
    header("Location: ./frontend/admin_dashboard.php");
    exit();
} elseif ($_SESSION['user_type'] === 'operador'){
    header("Location: ./frontend/pedidos.php");
    exit();
} else {
    header("Location: ./frontend/home.php");
    exit();
}

exit();
?>