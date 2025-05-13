<?php
include("./Controller/CartController.php");

$page = $_GET['page'] ?? 'home';

if ($page === 'cart') {
    $cartController = new CartController();
    $cartController->showCart(1); // Thay 1 bằng mã người dùng thực tế
} else {
    include("View/login.php"); // Trang mặc định
}
?>