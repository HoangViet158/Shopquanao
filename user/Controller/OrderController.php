<?php
include_once './Model/OrderModel.php';
session_start();

class OrderController {
    public function showOrderForm() {
        if (!isset($_SESSION['MaTK'])) {
            header("Location: ./user/view/login.php");
            exit();
        }

        $model = new OrderModel();
        $diaChi = $model->getDiaChi($_SESSION['MaTK']);
        $giohang = $model->getCart($_SESSION['MaTK']);

        include './View/order.php';
    }

    public function processOrder() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['MaTK'])) {
            $maTK = $_SESSION['MaTK'];
            $sdt = $_POST['sdt'] ?? '';
            $diachi = $_POST['diachi'] ?? '';
            $thanhtoan = $_POST['thanhtoan'] ?? '';

            $model = new OrderModel();
            $giohang = $model->getCart($maTK);

            if (empty($giohang)) {
                echo "<script>alert('Giỏ hàng trống!'); window.location.href='index.php?page=cart';</script>";
                return;
            }

            $maHD = $model->createHoaDon($maTK, $thanhtoan, $diachi);

            foreach ($giohang as $item) {
                $model->addCTHoaDon($maHD, $item);
            }

            $model->clearCart($maTK);

            echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php?page=cart';</script>";
        }
    }
}