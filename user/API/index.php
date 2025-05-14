<?php
// require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../config/connect.php');

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
            'categories' => isset($_GET['categories']) ? explode(',', $_GET['categories']) : [],
            'price' => $_GET['price'] ?? null,
            'genders' => isset($_GET['genders']) ? explode(',', $_GET['genders']) : [],
            'sizes' => isset($_GET['sizes']) ? explode(',', $_GET['sizes']) : []
        ];

        $result = $productController->filterProducts($filter, $page, $limit);
        echo json_encode($result);
        break;
    default:
        echo json_encode(['error' => 'Invalid type']);
        break;
}
