<?php
require_once(__DIR__ . "/../../config/connect.php");

class products_Model
{
    private $database = null;

    function __construct()
    {
        $this->database = new Database();
    }

    public function filterProducts($filter, $page = 1, $limit = 10)
    {
        $conn = $this->database->connection();

        // Câu SQL chính
        $sql = "SELECT sp.*, dm.TenDM, km.TenKM 
            FROM sanpham sp
            LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
            LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM
            left join phanloai pl on sp.MaPL = pl.MaPL
            WHERE sp.TrangThai = 1";

        // Câu SQL đếm tổng
        $countSql = "SELECT COUNT(*) as total 
                 FROM sanpham sp
                 WHERE sp.TrangThai = 1";

        $params = [];
        $types = "";
        $countParams = [];
        $countTypes = "";

        // --- Filter từ khóa ---
        if (!empty($filter['keyword'])) {
            $keyword = "%" . $filter['keyword'] . "%";
            $sql .= " AND LOWER(sp.TenSP) COLLATE utf8mb4_bin LIKE ?";
            $countSql .= " AND LOWER(sp.TenSP) COLLATE utf8mb4_bin LIKE ?";
            $params[] = $keyword;
            $countParams[] = $keyword;
            $types .= "s";
            $countTypes .= "s";
        }

        // --- Filter giá ---
        if (isset($filter['price'])) {
            switch ($filter['price']) {
                case 'under100':
                    $sql .= " AND sp.GiaBan < ?";
                    $countSql .= " AND sp.GiaBan < ?";
                    $params[] = 100000;
                    $countParams[] = 100000;
                    $types .= "i";
                    $countTypes .= "i";
                    break;
                case '100-500':
                    $sql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $countSql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $params[] = 100000;
                    $params[] = 500000;
                    $countParams[] = 100000;
                    $countParams[] = 500000;
                    $types .= "ii";
                    $countTypes .= "ii";
                    break;
                case '500-1000':
                    $sql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $countSql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $params[] = 500000;
                    $params[] = 1000000;
                    $countParams[] = 500000;
                    $countParams[] = 1000000;
                    $types .= "ii";
                    $countTypes .= "ii";
                    break;
                case 'over1000':
                    $sql .= " AND sp.GiaBan > ?";
                    $countSql .= " AND sp.GiaBan > ?";
                    $params[] = 1000000;
                    $countParams[] = 1000000;
                    $types .= "i";
                    $countTypes .= "i";
                    break;
            }
        }

        // --- Filter danh mục ---
        if (!empty($filter['categories'])) {
            // Chuyển categories thành mảng nếu chưa phải
            $categoriesArray = is_array($filter['categories']) ? $filter['categories'] : explode(',', $filter['categories']);
            
            if (!empty($categoriesArray)) {
                $placeholders = implode(',', array_fill(0, count($categoriesArray), '?'));
                $sql .= " AND sp.MaDM IN ($placeholders)";
                $countSql .= " AND sp.MaDM IN ($placeholders)";
                
                foreach ($categoriesArray as $category) {
                    $params[] = $category;
                    $countParams[] = $category;
                    $types .= "i";
                    $countTypes .= "i";
                }
            }
        }

        // --- Filter phân loại ---
        if (!empty($filter['types'])) {
        // Chuyển types thành mảng nếu chưa phải
        $typesArray = is_array($filter['types']) ? $filter['types'] : explode(',', $filter['types']);
        
        if (!empty($typesArray)) {
            $placeholders = implode(',', array_fill(0, count($typesArray), '?'));
            $sql .= " AND sp.MaPL IN ($placeholders)";
            $countSql .= " AND sp.MaPL IN ($placeholders)";
            
            foreach ($typesArray as $type) {
                $params[] = $type;
                $countParams[] = $type;
                $types .= "i";
                $countTypes .= "i";
            }
        }
    }


        // --- Filter giới tính ---
        if (!empty($filter['genders'])) {
            $genderConditions = [];
            foreach ($filter['genders'] as $g) {
                if ($g == 'male') $genderConditions[] = "sp.GioiTinh = 1";
                if ($g == 'female') $genderConditions[] = "sp.GioiTinh = 0";
                if ($g == 'unisex') $genderConditions[] = "sp.GioiTinh = 2";
            }
            if (!empty($genderConditions)) {
                $condition = " AND (" . implode(" OR ", $genderConditions) . ")";
                $sql .= $condition;
                $countSql .= $condition;
            }
        }

        // --- Filter nhiều size ---
        if (!empty($filter['sizes'])) {
            $sizePlaceholders = implode(',', array_fill(0, count($filter['sizes']), '?'));
            $subQuery = " AND sp.MaSP IN (
            SELECT DISTINCT ss.MaSP 
            FROM size_sanpham ss
            JOIN size s ON ss.MaSize = s.MaSize
            WHERE s.TenSize IN ($sizePlaceholders)
        )";
            $sql .= $subQuery;
            $countSql .= $subQuery;
            foreach ($filter['sizes'] as $size) {
                $params[] = $size;
                $countParams[] = $size;
                $types .= "s";
                $countTypes .= "s";
            }
        }

        // --- Phân trang ---
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        // Thực hiện query lấy dữ liệu
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $row['Anh'] = $this->getFirstImage($row['MaSP']);
            $products[] = $row;
        }

        // Thực hiện query đếm tổng
        $countStmt = $conn->prepare($countSql);
        if (!empty($countParams)) {
            $countStmt->bind_param($countTypes, ...$countParams);
        }
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $totalRow = $countResult->fetch_assoc();
        $totalProducts = $totalRow['total'];

        return [
            'products' => $products,
            'pagination' => [
                'total_products' => $totalProducts,
                'total_pages' => ceil($totalProducts / $limit),
                'current_page' => $page,
                'per_page' => $limit
            ]
        ];
    }

    public function getProductById($MaSP)
    {
        $sql = "SELECT sp.*, dm.TenDM, km.*, size_sanpham.*, s.TenSize
        FROM sanpham sp
        LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
        LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM
        LEFT JOIN size_sanpham ON sp.MaSP = size_sanpham.MaSP
        LEFT JOIN size s ON size_sanpham.MaSize = s.MaSize
        WHERE sp.MaSP = ? AND sp.TrangThai = 1";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Lấy danh sách ảnh
            $product['Anh'] = $this->getProductImages($MaSP);
            $product['Sizes'] = $this->getProductSizes($MaSP);
            return $product;
        }

        return null;
    }
    public function getProductSizes($MaSP)
    {
        $sql = "SELECT s.TenSize, size_sanpham.*
            FROM size_sanpham
            JOIN size s ON size_sanpham.MaSize = s.MaSize
            WHERE size_sanpham.MaSP = ?";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        $sizes = array();
        while ($row = $result->fetch_assoc()) {
            $sizes[] = $row;
        }

        return $sizes;
    }

    public function getProductImages($MaSP)
    {
        $sql = "SELECT * FROM anh WHERE MaSP = ? AND TrangThai = 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        $images = array();
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }

        return $images;
    }
    public function getFirstImage($maSP)
    {
        $conn = $this->database->connection();
        $sql = "SELECT Url FROM anh WHERE MaSP = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $maSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['Url'] : null;
    }
}
