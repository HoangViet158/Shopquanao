<?php
include("./Controller/CartController.php");
include("./Controller/OrderController.php");

// Lấy tham số trang từ URL, nếu không có thì mặc định là 'home'
$page = $_GET['page'] ?? 'home';

// Gán mã tài khoản cho người dùng để test
$maTK = 1;  // Gán mã tài khoản 1 (bạn có thể thay bằng mã người dùng thực tế)

if ($page === 'order') {
    $orderController = new OrderController();
    $orderController->showOrderForm();  // Hiển thị form đặt hàng
}
if ($page === 'process_order') {
    $controller = new OrderController();
    $controller->processOrder();  // Xử lý đặt hàng
}
if ($page === 'cart') {
    $cartController = new CartController();
    $cartController->showCart($maTK);  // Hiển thị giỏ hàng của người dùng có mã tài khoản là 1
} else {
    include("view/login.php");  // Trang đăng nhập mặc định
}