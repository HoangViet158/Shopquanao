<?php
require_once('../../config/connect.php');
$db=new Database();
$con=$db->connection();
require_once('../Controller/product_Controller.php');
$type=isset($_GET['type']) ? $_GET['type'] : null;
$productController=new product_Controller();
switch($type){
    case 'getAllProducts':
        $allProducts = $productController->getAllProducts();
        echo json_encode($allProducts);
        break;
}