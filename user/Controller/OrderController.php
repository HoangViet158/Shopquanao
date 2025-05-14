<?php
// include_once './Model/OrderModel.php';
// session_start();

// class OrderController {
//     public function showOrderForm() {
//         if (!isset($_SESSION['id'])) {
//             header("Location: ./user/view/login.php");
//             exit();
//         }

//         $model = new OrderModel();
//         $diaChi = $model->getDiaChi($_SESSION['id']);
//         $giohang = $model->getCart($_SESSION['id']);

//         include './View/order.php';
//     }

//     public function processOrder() {
//         if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['MaTK'])) {
//             $maTK = $_SESSION['id'];
//             $sdt = $_POST['sdt'] ?? '';
//             $diachi = $_POST['diachi'] ?? '';
//             $thanhtoan = $_POST['thanhtoan'] ?? '';

//             $model = new OrderModel();
//             $giohang = $model->getCart($maTK);

//             if (empty($giohang)) {
//                 echo "<script>alert('Giỏ hàng trống!'); window.location.href='index.php?page=cart';</script>";
//                 return;
//             }

//             $maHD = $model->createHoaDon($maTK, $thanhtoan, $diachi);

//             foreach ($giohang as $item) {
//                 $model->addCTHoaDon($maHD, $item);
//             }

//             $model->clearCart($maTK);

//             echo "<script>alert('Đặt hàng thành công!'); window.location.href='index.php?page=cart';</script>";
//         }
//     }
// }

require_once __DIR__ . '/../Model/OrderModel.php';

class OrderController {
    private $model;

    public function __construct() {
        $this->model = new OrderModel();
    }
    public function getCartItems($userId) {
        return $this->model->getCartItems($userId);
    }
    public function getUserAddress($userId) {
        return $this->model->getUserAddress($userId);
    }
    public function showOrderForm() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: /Shopquanao/user/View/login.php");
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $address = $this->model->getUserAddress($userId);
        $cartItems = $this->model->getCartItems($userId);

        require_once __DIR__ . '/../View/order_form.php';
    }

    public function processOrder($userId, $paymentMethod, $address, $phone) {
    try {
        
        // Validate input
        if (empty($paymentMethod)) {
            throw new Exception("Phương thức thanh toán không hợp lệ");
        }
        
        if (empty($address)) {
            throw new Exception("Địa chỉ không được để trống");
        }
        
        // Get cart items
        $cartItems = $this->model->getCartItems($userId);
        if (empty($cartItems)) {
            throw new Exception("Giỏ hàng trống");
        }
        
        // Create order
        $orderId = $this->model->createOrder($userId, $paymentMethod, $address, $phone);
        return $orderId;
        
    } catch (Exception $e) {
        throw $e;
    }
}
}
?>