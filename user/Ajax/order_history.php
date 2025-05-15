<?php
session_start();
include_once "../Controller/OrderController.php";

$controller = new OrderController();
$controller->showOrderHistory();
?>
