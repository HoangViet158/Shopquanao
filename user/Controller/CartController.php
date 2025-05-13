<?php
include_once(__DIR__ . '/../Model/cartModel.php');

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new CartModel();
    }

    public function showCart($userId) {
        $items = $this->cartModel->getCartItems($userId);
        include(__DIR__ . '/../View/cart.php');
    }
}
?>