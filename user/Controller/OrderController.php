<?php
require_once __DIR__ . '/../Model/OrderModel.php';

class OrderController
{
    private $model;

    public function __construct()
    {
        $this->model = new OrderModel();
    }
    public function getCartItems($userId)
    {
        return $this->model->getCartItems($userId);
    }
    public function getUserAddress($userId)
    {
        return $this->model->getUserAddress($userId);
    }
    public function showOrderForm()
    {
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

    public function processOrder($userId, $paymentMethod, $address, $phone)
    {
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
            foreach ($cartItems as $item) {
            $available = $this->model->checkAmountAvaible(
                $item['MaSP'], 
                $item['MaSize'], 
                $item['SoLuong']
            );
            
            if (!$available) {
                throw new Exception("Sản phẩm {$item['TenSP']} size {$item['TenSize']} không đủ số lượng trong kho, vui lòng giảm số lượng hoặc chọn sản phẩm khác");
            }
        }
            // Create order
            $orderId = $this->model->createOrder($userId, $paymentMethod, $address, $phone);
            return $orderId;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function showOrderHistory() {
        session_start();
        $userId = $_SESSION['user']['id'];
        if (!$userId) {
            header("Location: ./user/view/login.php");
            exit;
        }

        $orders = null;
        $detailOrder = null;
        $MaHD = $_GET['detail'] ?? null;

        if ($MaHD) {
            $detailOrder = $this->model->getOrderDetails($MaHD);
        } else {
            $orders = $this->model->getOrdersByUser($userId);
        }
        include '../View/order_history.php';
    }

    public function cancelOrder() {
        $MaHD = $_POST['MaHD'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$MaHD || !$userId) {
            echo "Dữ liệu không hợp lệ!";
            return;
        }

        $result = $this->model->cancelOrder($MaHD, $userId);
        echo $result ? "Hủy đơn hàng thành công!" : "Hủy đơn hàng thất bại!";
    }
    
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once __DIR__ . '/OrderController.php';
    $controller = new OrderController();

    if ($_POST['action'] === 'cancel') {
        $controller->cancelOrder();
    }
}