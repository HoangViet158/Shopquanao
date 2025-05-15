<?php
// include_once __DIR__ . '/../Model/OrderModel.php';

// class CartController {
//     public function showCart($maNguoiDung) {
//         $model = new OrderModel();
//         $cartItems = $model->getCart($maNguoiDung); // Gọi đúng hàm
//         include_once __DIR__ . '/../View/order.php';
//     }

//     public function placeOrder($maNguoiDung, $diaChi, $sdt, $thanhToan) {
//         $model = new OrderModel();
//         $cartItems = $model->getCart($maNguoiDung); // Gọi đúng hàm

//         if (empty($cartItems)) {
//             echo "<script>alert('Giỏ hàng trống!'); window.location.href='?page=cart';</script>";
//             return;
//         }

//         $maHD = $model->createHoaDon($maNguoiDung, $thanhToan, $diaChi); // Thứ tự tham số khớp model

//         foreach ($cartItems as $item) {
//             $model->addCTHoaDon($maHD, $item); // Gọi đúng tên hàm
//         }

//         $model->clearCart($maNguoiDung);

//         echo "<script>alert('Đặt hàng thành công!'); window.location.href='?page=success';</script>";
//     }
// }

require_once(__DIR__ . '/../Model/CartModel.php');

class CartController {
    private $model;

    public function __construct() {
        $this->model = new CartModel();
    }

    public function addCart($userId, $maSP, $maSize, $soLuong){
        return $this->model->addCart($userId, $maSP, $maSize, $soLuong);
    }

    public function getCart($userId) {
        return $this->model->getCartItems($userId);
    }

    public function deleteItem($userId, $maSP, $maSize) {
        return $this->model->deleteCartItem($userId, $maSP, $maSize);
    }

    public function updateItem($userId, $maSP, $maSize, $soLuong) {
        return $this->model->updateCartItem($userId, $maSP, $maSize, $soLuong);
    }
    
    public function clearCart($userId) {
        return $this->model->clearCart($userId);
    }
}
