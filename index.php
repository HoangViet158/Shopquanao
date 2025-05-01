<?php
session_start();

// Autoload các lớp
spl_autoload_register(function($class) {
    require_once __DIR__ . '/../app/Controller/' . $class . '.php';
    require_once __DIR__ . '/../app/Model/' . $class . '.php';
});

// Xử lý các route
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri == '/login') {
    $controller = new AuthController();
    $controller->login();
} elseif ($requestUri == '/register') {
    $controller = new AuthController();
    $controller->register();
} elseif ($requestUri == '/forgot-password') {
    $controller = new AuthController();
    $controller->forgotPassword();
} else {
    // Route khác
    echo "Trang không tồn tại";
}
