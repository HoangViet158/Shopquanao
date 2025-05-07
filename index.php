<?php
session_start();

// Autoload các lớp
spl_autoload_register(function($class) {
    require_once __DIR__ . '/../Controller/' . $class . '.php';
    require_once __DIR__ . '/../Model/' . $class . '.php';
});

// Xử lý các route
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri == '/login') {
    $controller = new AuthController();
    $controller->login();
} elseif ($requestUri == '/register') {
    $controller = new AuthController();
    $controller->register();
} elseif ($requestUri == '/forgot-password') {
    $controller = new AuthController();
    $controller->forgotPassword();
} elseif (strpos($requestUri, '/cart') === 0) {
    $controller = new CartController();

    if ($requestUri == '/cart/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->add();
    } elseif ($requestUri == '/cart/remove') {
        $controller->remove();
    } else {
        $controller->index();  // Hiển thị giỏ hàng
    }
} elseif ($requestUri == '/checkout') {
    $controller = new OrderController();
    $controller->checkout();
} elseif ($requestUri == '/checkout/place' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new OrderController();
    $controller->placeOrder();

}else {
    echo "Trang không tồn tại";
}


