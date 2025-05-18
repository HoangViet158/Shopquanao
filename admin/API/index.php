<?php
// require_once(__DIR__ . '/../../config/connect.php');
require_once(__DIR__ . '/../../config/connect.php');

$db = new Database();
$con = $db->connection();
require_once(__DIR__ . '/../Controller/permission_Controller.php');
require_once(__DIR__ . '/../Controller/statistic_Controller.php');
require_once(__DIR__ . '/../Controller/category_Controller.php');
require_once(__DIR__ . '/../Controller/promotion_Controller.php');
require_once(__DIR__ . '/../Controller/product_Controller.php');
require_once(__DIR__ . '/../Controller/goodsReceipt_Controller.php');
require_once(__DIR__ . '/../Controller/bill_Controller.php');
require_once(__DIR__ . '/../Controller/user_Controller.php');
require_once(__DIR__ . '/../../user/Controller/auth_Controller.php');

$billController = new bill_Controller();
$goodReceiptController = new goodsReceipt_Controller();
$type = isset($_GET['type']) ? $_GET['type'] : null;
$productController = new product_Controller();
$categoryController = new category_Controller();
$promotionController = new promotion_Controller();
$statisticConTroller = new statistic_Controller();
$userController = new user_Controller();
$permissionController = new permission_Controller();
$authController = new auth_Controller();

$MaKM = isset($_POST['MaKM']) && $_POST['MaKM'] !== "" ? $_POST['MaKM'] : null;
switch ($type) {
    case 'getAllProducts':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 6;  //đang test
        $allProducts = $productController->getAllProducts($page, $perPage);
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
                'MaPL' => (int)$_POST['MaPL'],
                'MaKM' => $MaKM,
                'GioiTinh' => (int)$_POST['GioiTinh'],
                'MoTa' => trim($_POST['MoTa']),
                'GiaBan' => $_POST['GiaBan'] ?? 0,
                'SoLuong' => $_POST['SoLuong'] ?? 0
            ];
            $maSP = $productController->AddProducts(
                $productData['MaKM'],
                $productData['MaDM'],
                $productData['MaPL'],
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
            'MaPL' => $_POST['MaPL'],
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
        $Detail = $_GET['MaPN'];
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
        $Detail = $_GET['MaHD'];
        echo json_encode($billController->getAllBillDetail($Detail));
        break;
    case 'searchGoodReceipt':
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $goodReceipt = $goodReceiptController->searchGoodsReceipt($searchTerm);
            echo json_encode($goodReceipt);
        } else {
            echo json_encode($_GET['search']);
            echo json_encode([]); // Trả về mảng rỗng nếu không có từ khóa tìm kiếm
        }
        break;

    case 'updateBillStatus':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nhận dữ liệu dạng form-data thay vì JSON
            $billId = $_POST['MaHD'] ?? null;
            $newStatus = $_POST['TrangThai'] ?? null;

            if (!$billId || !$newStatus) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
                exit;
            }

            $result = $billController->updateBillStatus($billId, $newStatus);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'filterBills':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nhận dữ liệu JSON
            $json = file_get_contents('php://input');
            $filters = json_decode($json, true);

            $status = $filters['status'] ?? null;
            $fromDate = $filters['fromDate'] ?? null;
            $toDate = $filters['toDate'] ?? null;
            $address = $filters['address'] ?? null;

            $filteredBills = $billController->filterBills($status, $fromDate, $toDate, $address);
            echo json_encode($filteredBills);
        }
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
            while ($row = $result->fetch_assoc()) {
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
        $result = $statisticConTroller->total_invoices($start, $end, $id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'getUser':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $search = !empty($_GET['search']) ? (string)$_GET['search'] : "";
        $result = $userController->getUser($limit, $offset, $search);
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
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $result = $userController->getUserById($id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'getAllPermission':
        $result = $permissionController->getAllPermission();
        $perrmission = [];
        while ($row = $result->fetch_assoc()) {
            $perrmission[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($perrmission);
        break;
    case 'getPermissionById':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $result = $permissionController->getPermissionById($id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'updateUser':
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $MaTK = (int)$data['MaNguoiDung'] ?? NULL;
        $TenTK   = $data['TenTK']   ?? '';
        $MatKhau = $data['MatKhau'] ?? '';
        $Email   = $data['Email']   ?? '';
        $DiaChi  = $data['DiaChi']  ?? '';
        $MaLoai  = (int)($data['MaLoai'] ?? 1);
        $MaQuyen = (int)($data['MaQuyen'] ?? 3);

        $success = $userController->editUser($MaTK, $TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
        break;
    case 'getAllType':
        $result = $permissionController->getAllType();
        $type = [];
        while ($row = $result->fetch_assoc()) {
            $type[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($type);
        break;
    case 'getTypeById':
        $id = isset($_GET['id']) ? (int)($_GET['id']) : NULL;
        $result = $permissionController->getTypeById($id);
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($row);
        break;
    case 'lockUser':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : NULL;
        $trangThai = isset($_GET['trangthai'])  ? (int)$_GET['trangthai'] : NULL;
        $result = $userController->deleteUser($trangThai, $id);;
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        break;
    case 'addPermission':
        $json = file_get_contents('php://input');
        $permission = json_decode($json, true);

        $tenQuyen = $permission['tenQuyen'] ?? '';
        $success = $permissionController->addPermission($tenQuyen);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
        break;
    case 'deletePermission':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : NULL;
        $result = $permissionController->deletePermission($id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        break;
    case 'isPermissionInUse':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : NULL;
        $result = $permissionController->isPermissionInUse($id);
        header('Content-Type: application/json');
        echo json_encode($result);
        break;
    case 'getAllFunction':
        $result = $permissionController->getAllFunction();
        $functionlist = [];
        while ($row = $result->fetch_assoc()) {
            $functionlist[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($functionlist);
        break;
    case 'getAction':
        $MaQuyen = isset($_GET['maquyen']) ? (int)$_GET['maquyen'] : NULL;
        $MaCTQ = isset($_GET['mactq']) ? (int)$_GET['mactq'] : NULL;
        $actions = [];
        $result = $permissionController->getAction($MaQuyen, $MaCTQ);
        while ($row = $result->fetch_assoc()) {
            $actions[] = $row;
        }
        header('Content-type: application/json');
        echo json_encode($actions);
        exit;
        break;
    case 'editPermission':
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);


        $MaQuyen = isset($data['maquyen']) ? (int)$data['maquyen'] : NULL;
        $MaCTQ = isset($data['mactq']) ? (int)$data['mactq'] : NULL;
        $action = isset($data['hanhdong']) ? $data['hanhdong'] : "";

        $result = $permissionController->editPermissionDetail($MaQuyen, $MaCTQ, $action);
        header('Content-type: application/json');
        echo json_encode(["success" => $result]);
        break;
    case 'addAndApplyPromotion':
        $name = $_POST['name'] ?? '';
        $value = $_POST['value'] ?? 0;
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $products = $_POST['products'] ?? [];

        // Nếu là JSON string hoặc dạng sai, cố gắng decode
        if (!is_array($products)) {
            $products = json_decode($products, true);
            if (!is_array($products)) $products = [];
        }

        $result = $promotionController->addAndApplyPromotion($name, $value, $startDate, $endDate, $products);
        echo json_encode($result);
        break;
    case 'checkPromotionProfit':
        $productId = $_GET['productId'];
        $discount = $_GET['discount'] ?? 0;
        $result = $promotionController->checkPromotionProfit($productId, $discount);
        echo json_encode($result);
        break;
    case 'getAllProductsForPromotion':
        $result = $promotionController->getAllProducts();
        echo json_encode($result);
        break;
    case 'updatePromotion':
        $promotionId = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $value = $_POST['value'] ?? 0;
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';
        $status = $_POST['status'] ?? 0;
        $products = $_POST['products'] ?? [];

        if (!is_array($products)) {
            $products = json_decode($products, true);
            if (!is_array($products)) $products = [];
        }

        $result = $promotionController->updateAndApplyPromotion(
            $promotionId,
            $name,
            $value,
            $startDate,
            $endDate,
            $status,
            $products
        );
        echo json_encode($result);
        break;

    case 'getPromotionDetail':
        $id = $_GET['id'] ?? 0;
        $result = $promotionController->getPromotionDetail($id);
        echo json_encode($result);
        break;

    case 'deletePromotion':
        $id = $_POST['id'] ?? 0;
        $result = $promotionController->deletePromotion($id);
        echo json_encode($result);
        break;
    case 'calculateSuggestedPrices':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($goodReceiptController->calculateSuggestedPrices($data));
        break;
    case 'login':
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $email = isset($data['email']) ? $data['email'] : "";
        $matkhau = isset($data['password']) ? $data['password'] : "";

        $result = $authController->loginValidate($email, $matkhau);
        if ($result) {
            session_start();
            $_SESSION['user'] = [
                'id' => $result['MaNguoiDung'],
                'username' => $result['TenTK'],
                'email' => $result['Email'],
                'permission' => $result['MaQuyen']
            ];
        }
        echo json_encode($result);
        break;
    case 'getSession':
        session_start();
        if (isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'success',
                'user' => $_SESSION['user']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not logged in'
            ]);
        }
        break;
    case 'changePassword':
        session_start();
        if (isset($_SESSION['user'])) {
            $id = $_SESSION['user']['id'];
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $matkhau = isset($data['password']) ? $data['password'] : "";

            $result = $userController->changePassword($id, $matkhau);
            echo json_encode(["success" => $result]);
            break;
        } else {
            echo json_encode(["success" => "Chưa đăng nhập"]);
            break;
        }
    case 'updateInformationUser':
        session_start();
        if (isset($_SESSION['user'])) {
            $id = $_SESSION['user']['id'];
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $username = isset($data['username']) ? $data['username'] : "";
            $address = isset($data['address']) ? $data['address'] : "";
            $result = $userController->updateInformationUser($id, $username, $address);
            echo json_encode(["success" => $result]);
            break;
        } else {
            echo json_encode(["success" => "Chưa đăng nhập"]);
            break;
        }
    case 'checkEmailExist':
        $email = $_GET['email'] ?? '';
        $result = $authController->getAuthByEmail($email);
        if ($result) {
            echo json_encode(["success" => true]);
            break;
        } else {
            echo json_encode(["success" => false]);
            break;
        }
    case 'getProductsByType':
        $id = $_GET['id'] ?? 0;
        $result = $productController->getProductsByType($id);
        echo json_encode($result);
        break;

    case 'getProductImage':
        $id = $_GET['id'] ?? 0;
        $result = $productController->getProductImages($id);
        echo json_encode($result);
        break;
    case 'getAllTypeByCategory':
        $id = $_GET['id'] ?? 0;
        $result = $categoryController->getAllTypeByCategory($id);
        echo json_encode($result);
        break;
}
