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
    public function getUserPhone($userId)
    {
        return $this->model->getUserPhone($userId);
    }
    public function showOrderForm()
    {
        @session_start(); // dấu @ sẽ ẩn mọi lỗi phát sinh từ hàm này

        if (!isset($_SESSION['user'])) {
            header("Location: ../View/login.php");
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $address = $this->model->getUserAddress($userId);
        $cartItems = $this->model->getCartItems($userId);

        $information = [
            'id' => $userId,
            'address' => $address,
            'cartItem' => $cartItems
        ];

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
                if($item['SoLuong'] <= 0){
                    throw new Exception("Sản phẩm {$item['TenSP']} size {$item['TenSize']} không hợp lệ");
                }
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

    public function showOrderHistory()
    {
        @session_start(); // dấu @ sẽ ẩn mọi lỗi phát sinh từ hàm này


        $userId = $_SESSION['user']['id'];
        if (!$userId) {
            header("Location: ../View/login.php");
            exit;
        }

        $orders = null;
        $detailOrder = null;
        $MaHD = $_GET['detail'] ?? null;

        if ($MaHD) {
            return $detailOrder = $this->model->getOrderDetails($MaHD);
        } else {
            return $orders = $this->model->getOrdersByUser($userId);
        }
    }

    public function cancelOrder()
    {
        $MaHD = $_POST['MaHD'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$MaHD || !$userId) {
            echo "Dữ liệu không hợp lệ!";
            return;
        }

        $result = $this->model->cancelOrder($MaHD, $userId);
        echo $result ? "Hủy đơn hàng thành công!" : "Hủy đơn hàng thất bại!";
    }
    public function getOrderDetail($orderId, $userId)
    {
        try {
            $order = $this->model->getOrderDetail($orderId, $userId);
            if (!$order) {
                throw new Exception("Đơn hàng không tồn tại hoặc không thuộc về bạn");
            }
            return $order;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
