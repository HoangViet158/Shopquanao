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
        $sql = "SELECT sp.*, dm.TenDM, km.TenKM 
            FROM sanpham sp
            LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
            LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM
            WHERE 1=1 AND sp.TrangThai = 1";

        $params = [];
        $types = "";

        // --- Filter từ khóa ---
        if (!empty($filter['keyword'])) {
            $keyword = "%" . $filter['keyword'] . "%";
            $sql .= " AND (sp.TenSP LIKE ?)";
            $params[] = $keyword;
            $types .= "s";
        }

        // --- Filter giá ---
        if (isset($filter['price'])) {
            switch ($filter['price']) {
                case 'under100':
                    $sql .= " AND sp.GiaBan < ?";
                    $params[] = 100000;
                    $types .= "i";
                    break;
                case '100-500':
                    $sql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $params[] = 100000;
                    $params[] = 500000;
                    $types .= "ii";
                    break;
                case '500-1000':
                    $sql .= " AND sp.GiaBan BETWEEN ? AND ?";
                    $params[] = 500000;
                    $params[] = 1000000;
                    $types .= "ii";
                    break;
                case 'over1000':
                    $sql .= " AND sp.GiaBan > ?";
                    $params[] = 1000000;
                    $types .= "i";
                    break;
            }
        }

        // --- Filter danh mục ---
        if (!empty($filter['categories'])) {
            $in = implode(',', array_fill(0, count($filter['categories']), '?'));
            $sql .= " AND sp.MaDM IN ($in)";
            foreach ($filter['categories'] as $cat) {
                $params[] = $cat;
                $types .= "i";
            }
        }

        // --- Filter giới tính ---
        if (!empty($filter['genders'])) {
            $genderConditions = [];
            foreach ($filter['genders'] as $g) {
                if ($g == 'male') $genderConditions[] = "sp.GioiTinh = 0";
                if ($g == 'female') $genderConditions[] = "sp.GioiTinh = 1";
                if ($g == 'unisex') $genderConditions[] = "sp.GioiTinh = 2";
            }
            if (!empty($genderConditions)) {
                $sql .= " AND (" . implode(" OR ", $genderConditions) . ")";
            }
        }

        // --- Filter nhiều size ---
        if (!empty($filter['sizes'])) {
            $sizePlaceholders = implode(',', array_fill(0, count($filter['sizes']), '?'));
            $sql .= " AND sp.MaSP IN (
            SELECT DISTINCT ss.MaSP 
            FROM size_sanpham ss
            JOIN size s ON ss.MaSize = s.MaSize
            WHERE s.TenSize IN ($sizePlaceholders)
        )";
            foreach ($filter['sizes'] as $size) {
                $params[] = $size;
                $types .= "s";
            }
        }

        // --- Phân trang ---
        $offset = ($page - 1) * $limit;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }
        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $row['Anh'] = $this->getFirstImage($row['MaSP']);
            $products[] = $row;
        }

        // --- Đếm tổng số sản phẩm ---
        $countSql = preg_replace('/LIMIT \? OFFSET \?/', '', $sql);
        $stmt = $conn->prepare($countSql);
        if ($types && $params) {
            $stmt->bind_param(substr($types, 0, -2), ...array_slice($params, 0, -2));
        }
        $stmt->execute();
        $stmt->store_result();
        $totalProducts = $stmt->num_rows;

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


    public function getProductById($id)
    {
        $conn = $this->database->connection();
        $sql = "SELECT sp.*, dm.TenDM, km.TenKM 
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM
                WHERE sp.MaSP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
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
