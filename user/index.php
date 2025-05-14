<?php
session_start();
require_once './Controller/OrderController.php';

$page = $_GET['page'] ?? 'home';

// Xử lý các hành động POST (cancel_order, process_order)
if ($page === 'cancel_order') {
    $controller = new OrderController();
    $controller->cancelOrder();
    exit();
}

if ($page === 'process_order') {
    $controller = new OrderController();
    $controller->processOrder();
    exit();
}
?>

<html lang="en">
    <?php
    switch ($page) {
        case 'home':
            include("View/home.php");
            break;
        case 'login':
            include("View/login.php");
            break;
        case 'register':
            include("View/register.php");
            break;
        case 'cart':
            include("View/cart.php");
            break;
        case 'order_history':
            $controller = new OrderController();
            $controller->showOrderHistory();
            break;
        case 'order':
            $controller = new OrderController();
            $controller->showOrderForm();
            break;
        default:
            include("View/home.php");
            break;
    }
    ?>
</html>