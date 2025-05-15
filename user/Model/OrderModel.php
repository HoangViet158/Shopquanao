<?php
// class OrderModel {
//     private $conn;

//     public function __construct() {
//         include_once __DIR__ . '/../../config/connect.php';
//         $db = new Database();
//         $this->conn = $db->connection();
//     }

//     // Lấy giỏ hàng (dùng trong cả CartController và OrderController)
//     public function getCart($maNguoiDung) {
//         $stmt = $this->conn->prepare("SELECT g.MaSP, g.MaSize, g.SoLuong, sp.GiaBan AS Gia
//                                       FROM giohang g
//                                       JOIN sanpham sp ON g.MaSP = sp.MaSP
//                                       WHERE g.MaNguoiDung = ?");
//         $stmt->bind_param("i", $maNguoiDung);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         $items = $result->fetch_all(MYSQLI_ASSOC);
//         $stmt->close();
//         return $items;
//     }

//     // Tạo hóa đơn
//     public function createHoaDon($maNguoiDung, $thanhToan, $diaChi) {
//         $stmt = $this->conn->prepare("INSERT INTO hoadon (MaTK, ThoiGian, ThanhToan, MoTa, TrangThai)
//                                       VALUES (?, NOW(), ?, ?, 1)");
//         $stmt->bind_param("iss", $maNguoiDung, $thanhToan, $diaChi);
//         $stmt->execute();
//         $maHD = $stmt->insert_id;
//         $stmt->close();
//         return $maHD;
//     }

//     // Thêm chi tiết hóa đơn
//     public function addCTHoaDon($maHD, $item) {
//         $thanhTien = $item['SoLuong'] * $item['Gia'];
//         $stmt = $this->conn->prepare("INSERT INTO cthoadon (MaHD, MaSP, SoLuongBan, DonGia, ThanhTien, MaSize)
//                                       VALUES (?, ?, ?, ?, ?, ?)");
//         $stmt->bind_param("iiiddi", $maHD, $item['MaSP'], $item['SoLuong'], $item['Gia'], $thanhTien, $item['MaSize']);
//         $stmt->execute();
//         $stmt->close();
//     }

//     // Xóa giỏ hàng
//     public function clearCart($maNguoiDung) {
//         $stmt = $this->conn->prepare("DELETE FROM giohang WHERE MaNguoiDung = ?");
//         $stmt->bind_param("i", $maNguoiDung);
//         $stmt->execute();
//         $stmt->close();
//     }

//     // Lấy địa chỉ người dùng (nếu bạn cần)
//     public function getDiaChi($maTK) {
//         $stmt = $this->conn->prepare("SELECT DiaChi FROM taikhoan WHERE MaTK = ?");
//         $stmt->bind_param("i", $maTK);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         $row = $result->fetch_assoc();
//         $stmt->close();
//         return $row ? $row['DiaChi'] : '';
//     }
// }

require_once __DIR__ . '/../../config/connect.php';

class OrderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getCartItems($userId) {
        $stmt = $this->conn->prepare("
            SELECT g.MaSP, g.MaSize, g.SoLuong, sp.TenSP, sp.GiaBan, sz.TenSize
            FROM giohang g
            JOIN sanpham sp ON g.MaSP = sp.MaSP
            JOIN size sz ON g.MaSize = sz.MaSize
            WHERE g.MaNguoiDung = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserAddress($userId) {
        $stmt = $this->conn->prepare("SELECT DiaChi FROM nguoidung WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['DiaChi'] : '';
    }

    public function createOrder($userId, $paymentMethod, $address, $phone) {
        $totalPay=0;
        $stmtCountTT = $this->conn->prepare("
            SELECT SUM(sp.GiaBan * g.SoLuong) AS Total
            FROM giohang g
            JOIN sanpham sp ON g.MaSP = sp.MaSP
            WHERE g.MaNguoiDung = ?
        ");
        $stmtCountTT->bind_param("i", $userId);
        $stmtCountTT->execute();
        $result = $stmtCountTT->get_result()->fetch_assoc();
        if ($result) {
            $totalPay = $result['Total'];
        }
        // Create invoice
        $stmt = $this->conn->prepare("
            INSERT INTO hoadon (MaTK, ThoiGian, ThanhToan, MoTa, TrangThai)
            VALUES (?, NOW(), ?, ?, 0)
        ");
        $stmt->bind_param("ids", $userId,$totalPay, $paymentMethod);
        $stmt->execute();
        $orderId = $stmt->insert_id;
        
        // Add order items
        $cartItems = $this->getCartItems($userId);
        foreach ($cartItems as $item) {
            $total = $item['GiaBan'] * $item['SoLuong'];
            $stmt = $this->conn->prepare("
                INSERT INTO cthoadon (MaHD, MaSP, SoLuongBan, DonGia, ThanhTien, MaSize)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "iiiddi", 
                $orderId, 
                $item['MaSP'], 
                $item['SoLuong'], 
                $item['GiaBan'], 
                $total, 
                $item['MaSize']
            );
            $stmt->execute();
        }
        $sqlInsertAddress="insert into nguoidung(MaNguoiDung,DiaChi) values(?,?,?)";
        $stmt = $this->conn->prepare($sqlInsertAddress);
        $stmt->bind_param("isi", $userId, $address);
        $stmt->execute();

        // Clear cart
        $stmt = $this->conn->prepare("DELETE FROM giohang WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        return $orderId;
    }
}
?>