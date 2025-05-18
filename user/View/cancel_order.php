<?php
session_start();
include_once "../Controller/OrderController.php";

$controller = new OrderController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->cancelOrder();
}
?>
