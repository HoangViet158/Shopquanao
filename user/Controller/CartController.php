<?php
include_once __DIR__ . '/../Model/OrderModel.php';

class CartController {
    public function showCart($maNguoiDung) {
        $model = new OrderModel();
        $cartItems = $model->getCart($maNguoiDung); // Gọi đúng hàm
        include_once __DIR__ . '/../View/order.php';
    }

    public function placeOrder($maNguoiDung, $diaChi, $sdt, $thanhToan) {
        $model = new OrderModel();
        $cartItems = $model->getCart($maNguoiDung); // Gọi đúng hàm

        if (empty($cartItems)) {
            echo "<script>alert('Giỏ hàng trống!'); window.location.href='?page=cart';</script>";
            return;
        }

        $maHD = $model->createHoaDon($maNguoiDung, $thanhToan, $diaChi); // Thứ tự tham số khớp model

        foreach ($cartItems as $item) {
            $model->addCTHoaDon($maHD, $item); // Gọi đúng tên hàm
        }

        $model->clearCart($maNguoiDung);

        echo "<script>alert('Đặt hàng thành công!'); window.location.href='?page=success';</script>";
    }
}