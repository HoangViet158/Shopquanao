<?php
require_once(__DIR__ . "/../../config/connect.php");
class product_Model
{

    private $database = null;
    public function __construct()
    {
        $this->database = new Database();
    }
    public function getAllProducts($page = 1, $perPage = 10) {
        $page = (int)$page;
    $perPage = (int)$perPage;
        // Tính toán offset
        $offset = ($page - 1) * $perPage;
        
        // Lấy tổng số sản phẩm (để tính tổng số trang)
        $countSql = "SELECT COUNT(*) as total FROM sanpham WHERE TrangThai = 1";
        $countResult = $this->database->execute($countSql);
        $totalProducts = $countResult->fetch_assoc()['total'];
        $totalPages = ceil($totalProducts / $perPage);
    
        // Lấy dữ liệu sản phẩm với phân trang
        $sql = "SELECT * FROM sanpham 
                LEFT JOIN khuyenmai ON sanpham.MaKM = khuyenmai.MaKM
                LEFT JOIN danhmuc ON sanpham.MaDM = danhmuc.MaDM
                WHERE sanpham.TrangThai = 1
                LIMIT $perPage OFFSET $offset";
    
        $result = $this->database->execute($sql);
        $data = array(
            'products' => array(),
            'pagination' => array(
                'total_products' => (int)$totalProducts,
                'total_pages' => $totalPages,
                'current_page' => (int)$page,
                'per_page' => (int)$perPage
            )
        );
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $maSP = $row['MaSP'];
                $sql_anh = "SELECT Url FROM anh WHERE MaSP = '$maSP'";
                $result_anh = $this->database->execute($sql_anh);
                $anh = array();
                if ($result_anh && $result_anh->num_rows > 0) {
                    while ($row_anh = $result_anh->fetch_assoc()) {
                        $anh[] = $row_anh['Url'];
                    }
                }
    
                $khuyenmai = array(
                    'MaKM' => $row['MaKM'],
                    'TenKM' => $row['TenKM'] ?? 'Không có',
                );
                $danhmuc = array(
                    'MaDM' => $row['MaDM'],
                    'TenDM' => $row['TenDM'] ?? 'Không có',
                );
    
                $data['products'][] = array(
                    'MaSP' => $row['MaSP'],
                    'TenSP' => $row['TenSP'],
                    'MoTa' => $row['MoTa'],
                    'GiaBan' => $row['GiaBan'],
                    'SoLuong' => $row['SoLuongTong'],
                    'GioiTinh' => $row['GioiTinh'],
                    'DanhMuc' => $danhmuc,
                    'KhuyenMai' => $khuyenmai,
                    'Anh' => $anh
                );
            }
        }
    
        return $data;
    }
    // public function getLastIdSP(){
    //     $sql="Select MaSP FROM sanpham ORDER BY sanpham.MaSP desc limit 1";
    //     $result=$this->database->execute($sql);
    //     return $result;
    //    }
    // public function getLastIdAnh(){
    //     $sql="Select MaAnh from anh order by anh.MaAnh desc limit 1";
    //     $result=$this->database->execute($sql);
    //     return $result;
    // }
    public function AddProducts($MaKM, $MaDM, $tenSP, $Mota, $GioiTinh)
    {
        $giaBan = 0;
        $soLuongTong = 0;
        $trangThai = 1;
        $ngayTao = date('Y-m-d');

        $MaKM = (empty($MaKM) || $MaKM === 'null') ? NULL : (int)$MaKM;

        if ($MaKM === NULL) {
            $sql = "INSERT INTO sanpham 
                    (MaDM, TenSP, MoTa, GiaBan, TrangThai, NgayTao, SoLuongTong, GioiTinh) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param(
                "issiisii",
                $MaDM,
                $tenSP,
                $Mota,
                $giaBan,
                $trangThai,
                $ngayTao,
                $soLuongTong,
                $GioiTinh
            );
        } else {
            $sql = "INSERT INTO sanpham 
                    (MaKM, MaDM, TenSP, MoTa, GiaBan, TrangThai, NgayTao, SoLuongTong, GioiTinh) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param(
                "iissiisii",
                $MaKM,
                $MaDM,
                $tenSP,
                $Mota,
                $giaBan,
                $trangThai,
                $ngayTao,
                $soLuongTong,
                $GioiTinh
            );
        }

        $result = $stmt->execute();
        $lastInsertId = $this->database->getInsertId();
        $stmt->close();

        return $result ? $lastInsertId : false;
    }

    public function addProductImage($MaSP, $Url)
    {
        $trangThai = 1;
        $sql = "INSERT INTO anh (MaSP, Url, TrangThai) VALUES (?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("isi", $MaSP, $Url, $trangThai);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function getProductById($MaSP)
    {
        $sql = "SELECT sp.*, dm.TenDM, km.TenKM 
                FROM sanpham sp
                LEFT JOIN danhmuc dm ON sp.MaDM = dm.MaDM
                LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM
                WHERE sp.MaSP = ?";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Lấy danh sách ảnh
            $product['Anh'] = $this->getProductImages($MaSP);

            return $product;
        }

        return null;
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

    public function updateProductInfo($MaSP, $TenSP, $MaDM, $MaKM, $GioiTinh, $MoTa)
    {
        $sql = "UPDATE sanpham SET 
                TenSP = ?, 
                MaDM = ?, 
                MaKM = ?, 
                GioiTinh = ?, 
                MoTa = ?
                WHERE MaSP = ?";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("sisisi", $TenSP, $MaDM, $MaKM, $GioiTinh, $MoTa, $MaSP);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function deleteProductImage($imageId)
    {
        $sqlSelect = "SELECT Url FROM anh WHERE MaAnh = ?";
        $stmtSelect = $this->database->prepare($sqlSelect);
        $stmtSelect->bind_param("i", $imageId);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();
        $image = $result->fetch_assoc();
        $stmtSelect->close();

        if ($image && file_exists("../../" . $image['Url'])) {
            unlink("../../" . $image['Url']);
        }

        $sqlDelete = "DELETE FROM anh WHERE MaAnh = ?";
        $stmtDelete = $this->database->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $imageId);
        $result = $stmtDelete->execute();
        $stmtDelete->close();

        return $result;
    }
    public function deleteProduct($maSP)
    {
        if ($this->checkProductExistInBill($maSP)) {
            $sql = "UPDATE sanpham SET TrangThai = 0 WHERE MaSP = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("i", $maSP);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            $sql = "DELETE FROM sanpham WHERE MaSP = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("i", $maSP);
            $sql_anh = "DELETE FROM anh WHERE MaSP = ?";
            $stmt_anh = $this->database->prepare($sql_anh);
            $stmt_anh->bind_param("i", $maSP);
            $stmt_anh->execute();
            $stmt_anh->close();
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    }
    public function checkProductExistInBill($maSP)
    {
        $sql = "SELECT * FROM cthoadon WHERE MaSP = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $maSP);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function searchByIdOrTenSP($keyword)
    {

        $sql = "SELECT *
                FROM sanpham
                LEFT JOIN khuyenmai ON sanpham.MaKM = khuyenmai.MaKM
                LEFT JOIN danhmuc ON sanpham.MaDM = danhmuc.MaDM
                WHERE LOWER(sanpham.TenSP) COLLATE utf8mb4_bin LIKE '%$keyword%'
                AND sanpham.TrangThai = 1";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $maSP = $row['MaSP'];
                $sql_anh = "SELECT Url FROM anh WHERE MaSP = '$maSP'";
                $result_anh = $this->database->execute($sql_anh);
                $anh = array();
                if ($result_anh && $result_anh->num_rows > 0) {
                    while ($row_anh = $result_anh->fetch_assoc()) {
                        $anh[] = $row_anh['Url'];
                    }
                }

                $khuyenmai = array(
                    'MaKM' => $row['MaKM'],
                    'TenKM' => $row['TenKM'] ?? 'Không có',
                );
                $danhmuc = array(
                    'MaDM' => $row['MaDM'],
                    'TenDM' => $row['TenDM'] ?? 'Không có',
                );

                $data[] = array(
                    'MaSP' => $row['MaSP'],
                    'TenSP' => $row['TenSP'],
                    'MoTa' => $row['MoTa'],
                    'GiaBan' => $row['GiaBan'],
                    'SoLuong' => $row['SoLuongTong'],
                    'GioiTinh' => $row['GioiTinh'],
                    'DanhMuc' => $danhmuc,
                    'KhuyenMai' => $khuyenmai,
                    'Anh' => $anh
                );
            }
        }
        return $data;
    }
}
