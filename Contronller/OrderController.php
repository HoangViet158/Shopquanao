<?php
class OrderController {
    public function checkout() {
        include_once __DIR__ . '/../View/order/checkout.php';
    }

    public function placeOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            $paymentMethod = $_POST['payment_method'];

            if (empty($cart)) {
                echo "Giỏ hàng trống.";
                return;
            }

            // Xử lý đơn hàng (giả lập)
            $orderModel = new Order();
            $orderId = $orderModel->create($cart, $paymentMethod);

            // Xóa giỏ hàng
            unset($_SESSION['cart']);

            include_once __DIR__ . '/../View/order/success.php';
        }
    }
}
