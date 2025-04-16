<?php
require_once('../../config/connect.php');
$db = new Database();
$con = $db->connection();

require_once('../Controller/category_Controller.php');
require_once('../Controller/promotion_Controller.php');
require_once('../Controller/product_Controller.php');
$type = isset($_GET['type']) ? $_GET['type'] : null;
$productController = new product_Controller();
$categoryController = new category_Controller();
$promotionController = new promotion_Controller();
$MaKM = isset($_POST['MaKM']) && $_POST['MaKM'] !== "" ? $_POST['MaKM'] : null;
switch ($type) {
    case 'getAllProducts':
        $allProducts = $productController->getAllProducts();
        echo json_encode($allProducts);
        break;
    case 'addProduct':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $MaKM = (!empty($_POST['MaKM']) && $_POST['MaKM'] !== 'null')
                ? (int)$_POST['MaKM']
                : NULL;

            $productData = [
                'TenSP' => trim($_POST['TenSP']),
                'MaDM' => (int)$_POST['MaDM'],
                'MaKM' => $MaKM,
                'GioiTinh' => (int)$_POST['GioiTinh'],
                'MoTa' => trim($_POST['MoTa']),
                'GiaBan' => $_POST['GiaBan'] ?? 0,
                'SoLuong' => $_POST['SoLuong'] ?? 0
            ];
            $maSP = $productController->AddProducts(
                $productData['MaKM'],
                $productData['MaDM'],
                $productData['TenSP'],
                $productData['MoTa'],
                $productData['GioiTinh']
            );

            if ($maSP && !empty($_FILES['image'])) {
                $uploadDir = '../../upload/products/';
                foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
                    $fileName = uniqid() . '_' . basename($_FILES['image']['name'][$key]);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $productController->addProductImage($maSP, 'upload/products/' . $fileName);
                    }
                }
            }

            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
        }
        break;


    case 'getAllCategories':
        echo json_encode($categoryController->getAllCategories());
        break;

    case 'getAllPromotions':
        echo json_encode($promotionController->getAllPromotions());
        break;
    case 'getProductById':
        if (isset($_GET['id'])) {
            $productId = (int)$_GET['id'];
            echo json_encode($productController->getProductById($productId));
        }
        break;

        case 'updateProduct':
            $MaSP = $_POST['MaSP'];
            $productData = [
                'TenSP' => $_POST['TenSP'],
                'MaDM' => $_POST['MaDM'],
                'MaKM' => ($_POST['MaKM'] === 'null') ? NULL : $_POST['MaKM'],
                'GioiTinh' => $_POST['GioiTinh'],
                'MoTa' => $_POST['MoTa']
            ];
            $deletedImages = $_POST['deletedImages'] ?? [];
            $newImages = [];
            if (!empty($_FILES['newImages'])) {
                $uploadDir = '../../upload/products/';
                $fileCount = count($_FILES['newImages']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['newImages']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = uniqid() . '_' . basename($_FILES['newImages']['name'][$i]);
                        $targetPath = $uploadDir . $fileName;
            
                        if (move_uploaded_file($_FILES['newImages']['tmp_name'][$i], $targetPath)) {
                            $relativePath = 'upload/products/' . $fileName;
                            $newImages[] = $relativePath;
                        }
                    }
                }
            }
            
            
            $result = $productController->updateProduct($MaSP, $productData, $deletedImages, $newImages);
            $result['debug'] = [
                'newImages' => $newImages,
                'fileCount' => isset($_FILES['newImages']) ? count($_FILES['newImages']['name']) : 0,
            ];
            
            
            echo json_encode($result);
            
            break;
            case 'deleteProduct':
                if (isset($_GET['MaSP'])) {
                    $productId = $_GET['MaSP']; 
                    $result = $productController->deleteProduct($productId);
                    echo json_encode([
                        'success' => $result,
                        'message' => $result ? 'Xóa thành công' : 'Xóa thất bại'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Thiếu tham số MaSP'
                    ]);
                }
                break;
    case 'searchProducts':
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $products = $productController->searchByIdOrTenSP($searchTerm);
            echo json_encode($products);
        } else {
            echo json_encode([]); // Trả về mảng rỗng nếu không có từ khóa tìm kiếm
        }
        break;
}
