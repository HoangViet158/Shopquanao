<?php
require_once(__DIR__ . "/../../config/connect.php");
class promotion_Model
{
    private $database = null;
    public function __construct()
    {
        $this->database = new Database();
    }
    public function getAllPromotions()
    {
        $sql = "select * from khuyenmai where  TrangThai != -1";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'MaKM' => $row['MaKM'],
                    'TenKM' => $row['TenKM'],
                    'TrangThai' => $row['TrangThai'],
                    'NgayBatDau' => $row['NgayBatDau'],
                    'NgayKetThuc' => $row['NgayKetThuc'],
                    'giaTriKM' => $row['giaTriKM'],
                );
            }
        }
        // var_dump($data);
        return $data;
    }
    public function addAndApplyPromotion($name, $value, $startDate, $endDate, $productIds = [])
{
    // Trường hợp không có sản phẩm - chỉ thêm khuyến mãi
    if (empty($productIds)) {
        $sql = "INSERT INTO khuyenmai(TenKM, giaTriKm, NgayBatDau, NgayKetThuc, TrangThai)
                VALUES(?, ?, ?, ?, 1)";
        $stmt = $this->database->prepare($sql);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn thêm khuyến mãi'];
        }

        $stmt->bind_param("ssss", $name, $value, $startDate, $endDate);
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Lỗi khi thêm khuyến mãi mới'];
        }

        return [
            'success' => true,
            'message' => 'Thêm khuyến mãi thành công (không áp dụng sản phẩm)',
            'promotionId' => $this->database->getInsertId()
        ];
    }

    // Kiểm tra lỗ vốn trước khi thêm
    $invalidProducts = [];
    foreach ($productIds as $productId) {
        $profitCheck = $this->checkPromotionProfit($productId, $value);
        if (!$profitCheck['valid']) {
            $invalidProducts[] = [
                'productId' => $productId,
                'details' => $profitCheck
            ];
        }
    }

    if (!empty($invalidProducts)) {
        $message = "Không thể thêm khuyến mãi do các sản phẩm sau sẽ bị lỗ: ";
        foreach ($invalidProducts as $item) {
            $message .= "SP {$item['productId']} (lỗ {$item['details']['loss_per_unit']}), ";
        }
        return [
            'success' => false,
            'message' => rtrim($message, ', '),
            'invalidProducts' => $invalidProducts
        ];
    }

    // Thêm khuyến mãi
    $sql = "INSERT INTO khuyenmai(TenKM, giaTriKm, NgayBatDau, NgayKetThuc, TrangThai)
            VALUES(?, ?, ?, ?, 1)";
    $stmt = $this->database->prepare($sql);
    if (!$stmt || !$stmt->bind_param("ssss", $name, $value, $startDate, $endDate) || !$stmt->execute()) {
        return ['success' => false, 'message' => 'Lỗi khi thêm khuyến mãi mới'];
    }

    $promotionId = $this->database->getInsertId();
    $successProducts = [];
    $failedProducts = [];

    // Áp dụng khuyến mãi cho từng sản phẩm
    foreach ($productIds as $productId) {
        $currentPrice = $this->getProductCurrentPrice($productId);
        $newPrice = $currentPrice * (1 - ($value / 100));

        $sql = "UPDATE sanpham SET MaKM = ?, GiaBan = ? WHERE MaSP = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt && $stmt->bind_param("idi", $promotionId, $newPrice, $productId) && $stmt->execute()) {
            $successProducts[] = $productId;
        } else {
            $failedProducts[] = $productId;
        }
    }

    if (!empty($failedProducts)) {
        return [
            'success' => false,
            'message' => 'Thêm khuyến mãi thành công nhưng có lỗi khi áp dụng cho một số sản phẩm',
            'promotionId' => $promotionId,
            'successProducts' => $successProducts,
            'failedProducts' => $failedProducts
        ];
    }

    return [
        'success' => true,
        'message' => 'Thêm và áp dụng khuyến mãi thành công',
        'promotionId' => $promotionId
    ];
}
    public function getProductCurrentPrice($productId)
    {
        $sql = "select GiaBan from sanpham where MaSP=?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['GiaBan'];
        }
        return null;
    }
    public function getCurrentCostPrice($productId)
    {
        $sql = "select sum(cpn.DonGia*cpn.SoLuongNhap)/sum(cpn.SoLuongNhap) as avg_cost
        from ctphieunhap cpn
        join size_sanpham ss on cpn.MaSP=ss.MaSP and cpn.MaSize=ss.MaSize
        where cpn.MaSP=? and ss.SoLuong>0";   //lay tb giá nhập
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['avg_cost'] ?? 0;
    }
    public function checkPromotionProfit($productId, $discountPercent)
    {
        $currentPrice = $this->getProductCurrentPrice($productId);
        $costPrice = $this->getCurrentCostPrice($productId);
        $discountPrice = $currentPrice * (1 - ($discountPercent / 100));
        return [
            'valid' => $discountPrice >= $costPrice,
            'current_price' => $currentPrice,
            'cost_price' => $costPrice,
            'discount_price' => $discountPrice,
            'loss_per_unit' => max(0, $costPrice - $discountPrice)
        ];
    }
    public function getAllProducts()
    {
        $sql = "select MaSP,TenSP,GiaBan from sanpham where TrangThai=1";
        return $this->database->execute($sql);
    }

    public function updateAndApplyPromotion($promotionId, $name, $value, $startDate, $endDate, $status, $productIds)
{
    $response = [
        'success' => true,
        'message' => '',
        'details' => [
            'removedProducts' => [],
            'addedProducts' => []
        ]
    ];

    // Lấy thông tin khuyến mãi hiện tại
    $currentPromotion = $this->getPromotionById($promotionId);
    if (!$currentPromotion) {
        $response['success'] = false;
        $response['message'] = 'Không tìm thấy khuyến mãi';
        return $response;
    }

    // Lấy danh sách sản phẩm đang áp dụng khuyến mãi này
    $currentProducts = $this->getProductsByPromotion($promotionId);
    $currentProductIds = array_column($currentProducts, 'MaSP');

    // Xác định sản phẩm cần xóa và thêm
    $productsToRemove = array_diff($currentProductIds, $productIds);
    $productsToAdd = array_diff($productIds, $currentProductIds);

    // Kiểm tra lỗ vốn cho các sản phẩm mới (nếu có)
    if (!empty($productsToAdd)) {
        $invalidProducts = [];
        foreach ($productsToAdd as $productId) {
            $check = $this->checkPromotionProfit($productId, $value);
            if (!$check['valid']) {
                $invalidProducts[] = [
                    'productId' => $productId,
                    'details' => $check
                ];
            }
        }

        if (!empty($invalidProducts)) {
            $message = "Không thể cập nhật khuyến mãi do các sản phẩm sau sẽ bị lỗ: ";
            foreach ($invalidProducts as $item) {
                $message .= "SP {$item['productId']} (lỗ {$item['details']['loss_per_unit']}), ";
            }
            return [
                'success' => false,
                'message' => rtrim($message, ', '),
                'invalidProducts' => $invalidProducts
            ];
        }
    }

    // Xử lý xóa khuyến mãi khỏi sản phẩm (nếu có)
    foreach ($productsToRemove as $productId) {
        $removeResult = $this->removePromotionFromProduct($productId);
        $response['details']['removedProducts'][] = [
            'productId' => $productId,
            'result' => $removeResult
        ];
        
        if (!$removeResult['success']) {
            $response['success'] = false;
        }
    }

    // Áp dụng khuyến mãi cho sản phẩm mới (nếu có)
    foreach ($productsToAdd as $productId) {
        $applyResult = $this->applyPromotionToProduct($productId, $promotionId, $value);
        $response['details']['addedProducts'][] = [
            'productId' => $productId,
            'status' => $applyResult['success'] ? 'success' : 'failed',
            'details' => $applyResult
        ];
        
        if (!$applyResult['success']) {
            $response['success'] = false;
        }
    }

    // Cập nhật thông tin khuyến mãi
    $sql = "UPDATE khuyenmai SET 
            TenKM = ?, 
            giaTriKM = ?, 
            NgayBatDau = ?, 
            NgayKetThuc = ?,
            TrangThai = ?
            WHERE MaKM = ?";
    $stmt = $this->database->prepare($sql);
    if (!$stmt || !$stmt->bind_param("sdssii", $name, $value, $startDate, $endDate, $status, $promotionId) || !$stmt->execute()) {
        $response['success'] = false;
        $response['message'] = 'Lỗi khi cập nhật thông tin khuyến mãi';
        return $response;
    }

    // Xử lý khi thay đổi trạng thái
    if ($status == 0) { // Kết thúc khuyến mãi
        $endResult = $this->endPromotionForAllProducts($promotionId);
        $response['statusChange'] = $endResult;
        if (!$endResult['success']) {
            $response['success'] = false;
        }
    }

    if ($response['success']) {
        $response['message'] = 'Cập nhật khuyến mãi thành công';
        if (empty($productIds)) {
            $response['message'] .= ' (Đã xóa hết sản phẩm khỏi khuyến mãi)';
        }
    } else {
        $response['message'] = 'Cập nhật khuyến mãi thành công nhưng có một số lỗi';
    }

    return $response;
}
    /**
     * Xóa khuyến mãi khỏi sản phẩm và khôi phục giá gốc
     */
    public function removePromotionFromProduct($productId)
    {
        $response = [
            'success' => true,
            'message' => '',
            'details' => []
        ];

        // Lấy thông tin sản phẩm
        $sql = "SELECT sanpham.GiaBan, sanpham.TenSP, khuyenmai.giaTriKM 
            FROM sanpham 
            JOIN khuyenmai ON sanpham.MaKM = khuyenmai.MaKM
            WHERE sanpham.MaSP = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $currentPrice = $row['GiaBan'];
            $discountPercent = $row['giaTriKM'];
            $originalPrice = $currentPrice / (1 - ($discountPercent / 100));

            $response['details'] = [
                'productId' => $productId,
                'productName' => $row['TenSP'],
                'discountedPrice' => $currentPrice,
                'discountPercent' => $discountPercent,
                'originalPrice' => $originalPrice
            ];

            // Cập nhật lại sản phẩm
            $sql = "UPDATE sanpham SET MaKM = NULL, GiaBan = ? WHERE MaSP = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("di", $originalPrice, $productId);

            if ($stmt->execute()) {
                $response['message'] = 'Xóa khuyến mãi thành công';
            } else {
                $response['success'] = false;
                $response['message'] = 'Lỗi khi xóa khuyến mãi';
                $response['error'] = $stmt->error;
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Không tìm thấy khuyến mãi cho sản phẩm này';
        }

        return $response;
    }

    public function endPromotionForAllProducts($promotionId)
    {
        $response = [
            'success' => true,
            'message' => '',
            'details' => []
        ];

        // Lấy giá trị khuyến mãi
        $sql = "SELECT giaTriKM FROM khuyenmai WHERE MaKM = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $promotionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $discountPercent = $result->fetch_assoc()['giaTriKM'];

        // Lấy danh sách sản phẩm áp dụng khuyến mãi này
        $products = $this->getProductsByPromotion($promotionId);

        foreach ($products as $product) {
            // Tính lại giá gốc
            $originalPrice = $product['GiaBan'] / (1 - ($discountPercent / 100));


            $productDetail = [
                'productId' => $product['MaSP'],
                'productName' => $product['TenSP'],
                'discountedPrice' => $product['GiaBan'],
                'discountPercent' => $discountPercent,
                'originalPrice' => $originalPrice
            ];

            // Cập nhật lại sản phẩm
            $sql = "UPDATE sanpham SET MaKM = NULL, GiaBan = ? WHERE MaSP = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("di", $originalPrice, $product['MaSP']);

            if ($stmt->execute()) {
                $productDetail['status'] = 'success';
            } else {
                $productDetail['status'] = 'failed';
                $productDetail['error'] = $stmt->error;
                $response['success'] = false;
            }

            $response['details'][] = $productDetail;
        }

        if ($response['success']) {
            $response['message'] = 'Kết thúc khuyến mãi thành công';
        } else {
            $response['message'] = 'Có lỗi xảy ra khi kết thúc khuyến mãi cho một số sản phẩm';
        }

        return $response;
    }

    /**
     * Áp dụng khuyến mãi cho sản phẩm
     */
    public function applyPromotionToProduct($productId, $promotionId, $discountPercent)
    {
        $response = [
            'success' => true,
            'message' => '',
            'details' => []
        ];

        // Lấy thông tin sản phẩm hiện tại
        $sql = "SELECT GiaBan, TenSP FROM sanpham WHERE MaSP = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $currentPrice = $product['GiaBan'];
        $newPrice = $currentPrice * (1 - ($discountPercent / 100));

        $response['details'] = [
            'productId' => $productId,
            'productName' => $product['TenSP'],
            'currentPrice' => $currentPrice,
            'discountPercent' => $discountPercent,
            'newPrice' => $newPrice
        ];

        // Kiểm tra lỗ vốn
        $check = $this->checkPromotionProfit($productId, $discountPercent);
        if (!$check['valid']) {
            $response['success'] = false;
            $response['message'] = 'Không thể áp dụng khuyến mãi do sẽ bị lỗ';
            $response['profitCheck'] = $check;
            return $response;
        }

        // Cập nhật sản phẩm
        $sql = "UPDATE sanpham SET MaKM = ?, GiaBan = ? WHERE MaSP = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("idi", $promotionId, $newPrice, $productId);

        if ($stmt->execute()) {
            $response['message'] = 'Áp dụng khuyến mãi thành công';
        } else {
            $response['success'] = false;
            $response['message'] = 'Lỗi khi áp dụng khuyến mãi';
            $response['error'] = $stmt->error;
        }

        return $response;
    }
    public function getPromotionById($promotionId)
    {
        $sql = "SELECT * FROM khuyenmai WHERE MaKM = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $promotionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // ds sp đg áp dụng km
    public function getProductsByPromotion($promotionId)
    {
        $sql = "SELECT MaSP, TenSP, GiaBan FROM sanpham WHERE MaKM = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $promotionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function deletePromotion($promotionId)
    {
        // Kiểm tra xem khuyến mãi có đang được áp dụng không
        $sql = "SELECT COUNT(*) as count FROM sanpham WHERE MaKM = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $promotionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $productCount = $result->fetch_assoc()['count'];

        // Lấy trạng thái hiện tại
        $sql = "SELECT TrangThai FROM khuyenmai WHERE MaKM = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $promotionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $status = $result->fetch_assoc()['TrangThai'];

        if ($status == 1 && $productCount > 0) {
            return ['success' => false, 'message' => 'Không thể xóa khuyến mãi đang hoạt động và có sản phẩm áp dụng'];
        }

        if ($status == 0 && $productCount > 0) {
            //  ẩn khuyến mãi
            $sql = "UPDATE khuyenmai SET TrangThai = -1 WHERE MaKM = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("i", $promotionId);
            $success = $stmt->execute();

            return [
                'success' => $success,
                'message' => $success ? 'Đã ẩn khuyến mãi' : 'Lỗi khi ẩn khuyến mãi'
            ];
        } else {
            // Xóa hoàn toàn khuyến mãi
            $sql = "DELETE FROM khuyenmai WHERE MaKM = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("i", $promotionId);
            $success = $stmt->execute();

            return [
                'success' => $success,
                'message' => $success ? 'Xóa khuyến mãi thành công' : 'Lỗi khi xóa khuyến mãi'
            ];
        }
    }
}
