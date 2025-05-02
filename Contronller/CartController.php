<?php
class CartController {
    public function index() {
        $cartModel = new Cart();
        $items = $cartModel->getCartItems();
        require_once __DIR__ . '/../View/cart/index.php';
    }

    public function add() {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'] ?? 1;

        $cartModel = new Cart();
        $cartModel->addToCart($productId, $quantity);

        header('Location: /cart');
        exit;
    }

    public function remove() {
        $productId = $_GET['product_id'];
        $cartModel = new Cart();
        $cartModel->removeFromCart($productId);

        header('Location: /cart');
        exit;
    }
}
