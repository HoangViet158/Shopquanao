<?php
require_once(__DIR__ . '/../../config/connect.php');
$db = new Database();
$con = $db->connection();
require_once(__DIR__ . '/../Controller/statistic_Controller.php');
require_once(__DIR__ . '/../Controller/category_Controller.php');
require_once(__DIR__ . '/../Controller/promotion_Controller.php');
require_once(__DIR__ . '/../Controller/product_Controller.php');
require_once(__DIR__ . '/../Controller/goodsReceipt_Controller.php');
require_once(__DIR__ . '/../Controller/bill_Controller.php');
require_once(__DIR__ . '/../Controller/user_Controller.php');
$billController=new bill_Controller();
$goodReceiptController=new goodsReceipt_Controller();
$type = isset($_GET['type']) ? $_GET['type'] : null;
$productController = new product_Controller();
$categoryController = new category_Controller();
$promotionController = new promotion_Controller();
$statisticConTroller = new statistic_Controller();
$userController = new user_Controller();
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
                $uploadDir = __DIR__ . '/../../upload/products/';
                foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
                    $fileName = uniqid() . '_' . basename($_FILES['image']['name'][$key]);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $productController->addProductImage($maSP, '/upload/products/' . $fileName);
                    }
                }
            }

            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
        }
        break;

        case 'addGoodReceipt':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $data = [
                        'MaNCC' => $_POST['MaNCC'] ?? null,
                        'TongTien' => $_POST['TongTien'] ?? 0,
                        'ProfitPercentage' => $_POST['ProfitPercentage'] ?? 0,
                        'products' => []
                    ];
        
                    if (isset($_POST['products'])) {
                        $data['products'] = json_decode($_POST['products'], true);
                        if ($data['products'] === null) {
                            throw new Exception("Lỗi parse products");
                        }
                    }
        
                    $result = $goodReceiptController->addGoodReceipt($data);
                    echo $result; // Không cần json_encode thêm nữa
        
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            }
            break;
        
            
        case 'getProductDiscount':
            if (isset($_GET['MaSP'])) {
                $productId = (int)$_GET['MaSP'];
                $discount = $goodReceiptController->getProductDiscount($productId);
                echo json_encode(['discount' => $discount]);
            }
            break;
    
        case 'getProductDiscount':
            if (isset($_GET['MaSP'])) {
                $productId = (int)$_GET['MaSP'];
                $sql = "SELECT km.giaTriKM 
                        FROM sanpham sp 
                        JOIN khuyenmai km ON sp.MaKM = km.MaKM 
                        WHERE sp.MaSP = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $discount = 0;
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $discount = $row['giaTriKM'];
                }
                
                echo json_encode(['discount' => (float)$discount]);
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
                $uploadDir = __DIR__ . '/../../upload/products/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileCount = count($_FILES['newImages']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['newImages']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = uniqid() . '_' . basename($_FILES['newImages']['name'][$i]);
                        $targetPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['newImages']['tmp_name'][$i], $targetPath)) {
                            $relativePath = '/upload/products/' . $fileName;
                            $newImages[] = $relativePath;
                        } else {
                            error_log("Upload failed: " . print_r(error_get_last(), true));
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
    case 'loadGoodsReceiptList':
        echo json_encode($goodReceiptController->getAllGoodsReceipt());
        break;
    case 'getGoodReceiptDetail':
        $Detail=$_GET['MaPN'];
        echo json_encode($goodReceiptController->getAllGoodsReceiptDetail($Detail));
        break;
    case 'getAllTenSP':
        echo json_encode($goodReceiptController->getAllTenSP());
        break;
    case 'getAllSize':
        echo json_encode($goodReceiptController->getAllSize());    
        break;
    case 'getAllProvider':
        echo json_encode($goodReceiptController->getAllProvider());
        break;
    case 'getAllBill':
        echo json_encode($billController->getAllBill());
        break;
    case 'getAllBillDetail':
        $Detail=$_GET['MaHD'];
        echo json_encode($billController->getAllBillDetail($Detail));
        break;
    case 'top5users':
        $start = new DateTime($_GET['daystart'] ?? '2024-01-01');
        $end   = new DateTime($_GET['dayend'] ?? 'now');
        $topUsers = $statisticConTroller->getTopUserCost($start, $end);
        
        if ($topUsers) {
            $result = [];
            while ($row = $topUsers->fetch_assoc()) {
                $result[] = $row;
            }
            header('Content-Type: application/json'); // ✅ Đảm bảo kiểu JSON
            echo json_encode($result);
        } else {
            header('Content-Type: application/json'); // ✅ Đảm bảo kiểu JSON
            echo json_encode(['error' => 'Không có dữ liệu top 5 người dùng']);
        }
        break;
    case 'loadInvoices':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $start = new DateTime($_GET['daystart'] ?? '2024-01-01');
        $end   = new DateTime($_GET['dayend'] ?? 'now');
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

        $result =  $statisticConTroller->getInvoices($limit, $offset, $start, $end, $id);
        if ($result) {
            $invoices = [];
            while ($row = $result->fetch_assoc()){
                $invoices[] = $row;
            }
            header('Content-Type: application/json');
            echo json_encode($invoices);
        }
        break;
    case 'monthlyRevenue':
        $date  = isset($_GET['date']);
        header('Content-Type: application/json'); 
        $statisticConTroller->monthly_revenue($date);
        break;
    case 'totalInvoices':
        $start = new DateTime($_GET['daystart'] ?? '2024-01-01');
        $end   = new DateTime($_GET['dayend'] ?? 'now');
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $result = $statisticConTroller->total_invoices($start,$end,$id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'getUser':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $search = !empty($_GET['search']) ? (string)$_GET['search'] : "";
        $result = $userController->getUser($limit,$offset,$search);
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    
        header('Content-Type: application/json');
        echo json_encode($users);
        break;
    case 'getTotalUser':
        $search = !empty($_GET['search']) ? (string)$_GET['search'] : "";
        $result = $userController->getTotalUser($search);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'addUser':
        // Đọc toàn bộ raw POST body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        $TenTK   = $data['TenTK']   ?? '';
        $MatKhau = $data['MatKhau'] ?? '';
        $Email   = $data['Email']   ?? '';
        $DiaChi  = $data['DiaChi']  ?? '';
        $MaLoai  = (int)($data['MaLoai'] ?? 1);
        $MaQuyen = (int)($data['MaQuyen'] ?? 3);
    
        $success = $userController->addUser($TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
        break;
    case 'getUserById':
        $id = $isset($_GET['id']) ? (int)$_GET['id'] : null;
        $result = $userController->getUserById($id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
}       
