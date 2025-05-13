<?php
require_once(__DIR__ . "/../../config/connect.php");
class goodsReceipt_Model
{
    private $database = null;
    function __construct()
    {
        return $this->database = new Database();
    }
    public function getAllGoodsReceipt()
    {
        $sql = "select * from phieunhap
        inner join taikhoan on phieunhap.MaTK=taikhoan.MaTK
        inner join nhacungcap on phieunhap.MaNCC=nhacungcap.MaNCC
        where phieunhap.TrangThai=1
        ";
        $data = array();
        $result = $this->database->execute($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $MaPN = $row['MaPN'];
                $taikhoan = array(
                    'MaTK' => $row['MaTK'],
                    'TenTK' => $row['TenTK'],
                );
                $nhacungcap = array(
                    'MaNCC' => $row['MaNCC'],
                    'TenNCC' => $row['TenNCC'],
                );
                $ThanhToan = $row['ThanhToan'];
                $ThoiGian = $row['ThoiGian'];
                $data[] = array(
                    'MaPN' => $MaPN,
                    'taikhoan' => $taikhoan,
                    'nhacungcap' => $nhacungcap,
                    'ThanhToan' => $ThanhToan,
                    'ThoiGian' => $ThoiGian,
                );
            }
        }
        return $data;
    }
    public function getAllTenSP()
    {
        $sql = "select TenSP,MaSP from sanpham";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sanpham = array(
                    'MaSP' => $row['MaSP'],
                    'TenSP' => $row['TenSP']
                );
                $data[] = $sanpham;
            }
        }
        return $data;
    }
    public function getAllProvider()
    {
        $sql = "select MaNCC,TenNCC from nhacungcap";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nhacungcap = array(
                    'MaNCC' => $row['MaNCC'],
                    'TenNCC' => $row['TenNCC'],
                );
                $data[] = $nhacungcap;
            }
        }
        return $data;
    }
    public function getAllSize()
    {
        $sql = "select TenSize,MaSize from size";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $size = array(
                    'MaSize' => $row['MaSize'],
                    'TenSize' => $row['TenSize'],
                );
                $data[] = $size;
            }
        }
        return $data;
    }
    public function getAllGoodsReceiptDetail($MaPN)
    {
        $sql = "select * from ctphieunhap
        inner join sanpham on sanpham.MaSP=ctphieunhap.MaSP
        inner join size on size.MaSize=ctphieunhap.MaSize
        where MaPN=$MaPN        
        ";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $MaPN = $row['MaPN'];
                $sanpham = array(
                    'MaSP' => $row['MaSP'],
                    'TenSP' => $row['TenSP'],
                );
                $size = array(
                    'MaSize' => $row['MaSize'],
                    'TenSize' => $row['TenSize']
                );
                $DonGia = $row['DonGia'];
                $SoLuongNhap = $row['SoLuongNhap'];
                $ThanhTien = $row['ThanhTien'];

                $data[] = array(
                    'MaPN' => $MaPN,
                    'sanpham' => $sanpham,
                    'size' => $size,
                    'DonGia' => $DonGia,
                    'SoLuongNhap' => $SoLuongNhap,
                    'ThanhTien' => $ThanhTien,
                );
            }
        }
        return $data;
    }
    public function addGoodReceiptRow($MaTK, $MaNCC, $TongTien)
    {
        $sql = "INSERT INTO phieunhap (MaTK, MaNCC, ThanhToan, ThoiGian, TrangThai) 
                VALUES (?, ?, ?, NOW(), 1)";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("iid", $MaTK, $MaNCC, $TongTien);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }
    public function addGoodReceiptDetailRow($MaPN, $MaSP, $MaSize, $DonGia, $SoLuongNhap, $ThanhTien)
    {
        $sql = "insert into ctphieunhap(MaPN,MaSP,MaSize,DonGia,SoLuongNhap,ThanhTien)
        values(?,?,?,?,?,?)
        ";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("iiidid", $MaPN, $MaSP, $MaSize, $DonGia, $SoLuongNhap, $ThanhTien);
        return $stmt->execute();
    }
    public function updateProductQuantity($MaSP, $MaSize, $SoLuongNhap)
    {
        $checkSql = "Select * from size_sanpham where MaSP=? and MaSize=?";
        $checkStmt = $this->database->prepare($checkSql);
        $checkStmt->bind_param("ii", $MaSP, $MaSize);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            $updateSql = "update size_sanpham set SoLuong=SoLuong+?
            where MaSP=? and MaSize=?
            ";
            $updateStmt = $this->database->prepare($updateSql);
            $updateStmt->bind_param("iii", $SoLuongNhap, $MaSP, $MaSize);
            return $updateStmt->execute();
        } else {
            $insertSql = "insert into size_sanpham (MaSP,MaSize,SoLuong) 
            values (?,?,?);
            ";
            $insertStmt = $this->database->prepare($insertSql);
            $insertStmt->bind_param("iii", $MaSP, $MaSize, $SoLuongNhap);
            return $insertStmt->execute();
        }
    }
    public function updatePriceandAmount($MaSP, $GiaBan)
    {
        $SoLuongTong = 0;
        $sql = "SELECT SoLuong FROM size_sanpham WHERE MaSP = ?"; // Thêm tham số ?
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $SoLuongTong += $row['SoLuong']; // Sửa lại cách cộng dồn
            }
        }

        $sqlUpdate = "UPDATE sanpham SET GiaBan = ?, SoLuongTong = ? WHERE MaSP = ?";
        $stmtUpdate = $this->database->prepare($sqlUpdate);
        $stmtUpdate->bind_param("dii", $GiaBan, $SoLuongTong, $MaSP);
        return $stmtUpdate->execute();
    }
    public function getProductDiscount($MaSP)
    {
        $sql = "SELECT km.giaTriKM 
                FROM sanpham sp 
                LEFT JOIN khuyenmai km ON sp.MaKM = km.MaKM 
                WHERE sp.MaSP = ?";

        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $MaSP);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['giaTriKM'] ?? 0;
        }
        return 0;
    }
    public function searchGoodsReceipt($keyword)
    {
        $sql = "select * from phieunhap
        inner join taikhoan on phieunhap.MaTK=taikhoan.MaTK
        inner join nhacungcap on phieunhap.MaNCC=nhacungcap.MaNCC
        where LOWER(taikhoan.TenTK) COLLATE utf8mb4_bin LIKE '%$keyword%' or lower(nhacungcap.TenNCC) collate utf8mb4_bin like '%$keyword%'
         and phieunhap.TrangThai=1";
        $result = $this->database->execute($sql);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $MaPN = $row['MaPN'];
                $taikhoan = array(
                    'MaTK' => $row['MaTK'],
                    'TenTK' => $row['TenTK']
                );
                $nhacungcap = array(
                    'MaNCC' => $row['MaNCC'],
                    'TenNCC' => $row['TenNCC']
                );
                $ThanhToan = $row['ThanhToan'];
                $ThoiGian = $row['ThoiGian'];
                $data[] = array(
                    'MaPN' => $MaPN,
                    'taikhoan' => $taikhoan,
                    'nhacungcap' => $nhacungcap,
                    'ThanhToan' => $ThanhToan,
                    'ThoiGian' => $ThoiGian,
                );
            }
        }
        return $data;
    }
    // public function searchGoodsReceiptDetail($MaPN,$keyword){
    //     $sql="select * from ctphieunhap
    //     inner join sanpham on sanpham.MaSP=ctphieunhap.MaSP
    //     inner join size on size.MaSize=ctphieunhap.MaSize
    //     where MaPN=$MaPN and lower(sanpham.TenSP) collate utf8mb4_bin like '%$keyword%' ";
    // }
    public function getAverageCostPrice($MaSP)
{
    $sql = "SELECT SUM(ctpn.SoLuongNhap * ctpn.DonGia) as total_value,
                   SUM(ctpn.SoLuongNhap) as total_quantity
            FROM ctphieunhap ctpn
            WHERE ctpn.MaSP = ?";

    $stmt = $this->database->prepare($sql);
    $stmt->bind_param("i", $MaSP);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $totalValue = $row['total_value'] ?? 0;
    $totalQuantity = $row['total_quantity'] ?? 0;

    if ($totalQuantity > 0) {
        return $totalValue / $totalQuantity;
    }

    return 0;
}

    public function getCurrentQuantity($productId)
    {
        try {
            $sql = "SELECT SUM(SoLuong) as total_quantity 
                FROM size_sanpham
                WHERE MaSP = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("s", $productId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result['total_quantity'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    // Add this function to the goodsReceipt_Model class
    public function getDetailedPriceCalculation($MaSP, $profitPercentage)
    {
        $averageCostPrice = $this->getAverageCostPrice($MaSP);
        $discount = $this->getProductDiscount($MaSP);
        $currentQuantity = $this->getCurrentQuantity($MaSP);
        $suggestedPrice = $averageCostPrice * (1 + ($profitPercentage / 100) - ($discount / 100));

        return [
            'productId' => $MaSP,
            'averageCostPrice' => $averageCostPrice,
            'discount' => $discount,
            'currentQuantity' => $currentQuantity,
            'profitPercentage' => $profitPercentage,
            'suggestedPrice' => $suggestedPrice,
            'calculation' => "$averageCostPrice × (1 + ($profitPercentage/100) - ($discount/100)) = $suggestedPrice"
        ];
    }
}
