<?php
class Cart {
    public function addToCart($productId, $quantity) {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
    }

    public function removeFromCart($productId) {
        unset($_SESSION['cart'][$productId]);
    }

    public function getCartItems() {
        return $_SESSION['cart'] ?? [];
    }

    public function clearCart() {
        unset($_SESSION['cart']);
    }
}
