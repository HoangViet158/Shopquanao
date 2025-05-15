<?php
// require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../config/connect.php');
session_start();
require_once(__DIR__ . '/../Controller/CartController.php');
require_once(__DIR__ . '/../Controller/OrderController.php');


header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

$userId = $_SESSION['user']['id'] ?? 0;
$Cartcontroller = new CartController();
$orderController = new OrderController();
$db = new Database();
$con = $db->connection();
require_once(__DIR__ . '/../Controller/products_Controller.php');
$type = isset($_GET['type']) ? $_GET['type'] : null;
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 6;
$productController = new product_Controller();
switch ($type) {
    case 'filter':
        $filter = [
            'keyword' => $_GET['keyword'] ?? null,
            'categories' => isset($_GET['categories']) ? explode(',', $_GET['categories']) : [],
            'price' => $_GET['price'] ?? null,
            'genders' => isset($_GET['genders']) ? explode(',', $_GET['genders']) : [],
            'sizes' => isset($_GET['sizes']) ? explode(',', $_GET['sizes']) : []
        ];

        $result = $productController->filterProducts($filter, $page, $limit);
        echo json_encode($result);
        break;
    case 'getProductDetail':
        $id = $_GET['id'] ?? 0;
        $result = $productController->getProductDetail($id);
        echo json_encode($result);
        break;
    case 'getCart':
        $cartItems = $Cartcontroller->getCart($userId);
        if (empty($cartItems)) {
            echo json_encode(['error' => 'Giỏ hàng trống']);
            exit;
        }
        foreach ($cartItems as &$item) {
            $item['HinhAnh'] = explode(',', $item['HinhAnh']);
            $item['GiaBan'] = number_format($item['GiaBan'], 0, ',', '.');
            $item['SoLuongTon'] = number_format($item['SoLuongTon'], 0, ',', '.');
        }
        echo json_encode($cartItems);
        break;
    case 'deleteCartItem':
        $maSP = $_POST['maSP'] ?? null;
        $maSize = $_POST['maSize'] ?? null;

        if ($Cartcontroller->deleteItem($userId, $maSP, $maSize)) {
            echo json_encode(['success' => 'Xóa sản phẩm thành công']);
        } else {
            echo json_encode(['error' => 'Xóa sản phẩm thất bại']);
        }
        break;
    case 'updateCartItem':
        $maSP = $_POST['maSP'] ?? null;
        $maSize = $_POST['maSize'] ?? null;
        $soLuong = $_POST['soLuong'] ?? null;

        if ($Cartcontroller->updateItem($userId, $maSP, $maSize, $soLuong)) {
            echo json_encode(['success' => 'Cập nhật sản phẩm thành công']);
        } else {
            echo json_encode(['error' => 'Cập nhật sản phẩm thất bại']);
        }
        break;
    case 'createOrder':
        $paymentMethod = $_POST['thanhtoan'] ?? '';
        $address = $_POST['diachi'] ?? '';
        $phone = $_POST['sdt'] ?? '';

        try {
            $orderId = $orderController->processOrder($userId, $paymentMethod, $address, $phone);
            echo json_encode(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
    default:
        echo json_encode(['error' => 'Invalid type']);
        break;
    case 'addCart':
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $userId = $_SESSION['user']['id'] ?? NULL;
        $productId = isset($data['maSP']) ? $data['maSP'] : NULL;
        $sizeId = isset($data['maSize']) ? $data['maSize'] : NULL;
        $amount = isset($data['soluong']) ? $data['soluong'] : 0;

        $result = $Cartcontroller->addCart($userId, $productId, $sizeId, $amount);
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit;
        break;

}
