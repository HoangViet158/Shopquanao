<?php
class OrderModel {
    private $conn;

    public function __construct() {
        include_once __DIR__ . '/../../config/connect.php';
        $db = new Database();
        $this->conn = $db->connection();
    }

    // Lấy giỏ hàng (dùng trong cả CartController và OrderController)
    public function getCart($maNguoiDung) {
        $stmt = $this->conn->prepare("SELECT g.MaSP, g.MaSize, g.SoLuong, sp.GiaBan AS Gia
                                      FROM giohang g
                                      JOIN sanpham sp ON g.MaSP = sp.MaSP
                                      WHERE g.MaNguoiDung = ?");
        $stmt->bind_param("i", $maNguoiDung);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $items;
    }

    // Tạo hóa đơn
    public function createHoaDon($maNguoiDung, $thanhToan, $diaChi) {
        $stmt = $this->conn->prepare("INSERT INTO hoadon (MaTK, ThoiGian, ThanhToan, MoTa, TrangThai)
                                      VALUES (?, NOW(), ?, ?, 1)");
        $stmt->bind_param("iss", $maNguoiDung, $thanhToan, $diaChi);
        $stmt->execute();
        $maHD = $stmt->insert_id;
        $stmt->close();
        return $maHD;
    }

    // Thêm chi tiết hóa đơn
    public function addCTHoaDon($maHD, $item) {
        $thanhTien = $item['SoLuong'] * $item['Gia'];
        $stmt = $this->conn->prepare("INSERT INTO cthoadon (MaHD, MaSP, SoLuongBan, DonGia, ThanhTien, MaSize)
                                      VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiddi", $maHD, $item['MaSP'], $item['SoLuong'], $item['Gia'], $thanhTien, $item['MaSize']);
        $stmt->execute();
        $stmt->close();
    }

    // Xóa giỏ hàng
    public function clearCart($maNguoiDung) {
        $stmt = $this->conn->prepare("DELETE FROM giohang WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $maNguoiDung);
        $stmt->execute();
        $stmt->close();
    }

    // Lấy địa chỉ người dùng (nếu cần)
    public function getDiaChi($maTK) {
        $stmt = $this->conn->prepare("SELECT DiaChi FROM taikhoan WHERE MaTK = ?");
        $stmt->bind_param("i", $maTK);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ? $row['DiaChi'] : '';
    }
    // Lấy danh sách đơn hàng của người dùng
    public function getOrdersByUser($maTK) {
        $orders = [];
        $stmt = $this->conn->prepare("SELECT * FROM hoadon WHERE MaTK = ? ORDER BY ThoiGian DESC");
        $stmt->bind_param("i", $maTK);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['chitiet'] = $this->getChiTietHoaDon($row['MaHD']);
            $orders[] = $row;
        }
        $stmt->close();
        return $orders;
    }

    // Lấy chi tiết đơn hàng
    private function getChiTietHoaDon($maHD) {
        $stmt = $this->conn->prepare("SELECT c.*, sp.TenSP, s.TenSize 
            FROM cthoadon c
            JOIN sanpham sp ON c.MaSP = sp.MaSP
            JOIN size s ON c.MaSize = s.MaSize
            WHERE MaHD = ?");
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $details;
    }

    // Huỷ đơn hàng
    public function cancelOrder($maHD) {
        // 1. Lấy chi tiết để hoàn hàng
        $details = $this->getChiTietHoaDon($maHD);
        foreach ($details as $item) {
            $stmt = $this->conn->prepare("UPDATE size_sanpham SET SoLuong = SoLuong + ? WHERE MaSP = ? AND MaSize = ?");
            $stmt->bind_param("iii", $item['SoLuongBan'], $item['MaSP'], $item['MaSize']);
            $stmt->execute();
            $stmt->close();
        }

        // 2. Xoá chi tiết hóa đơn
        $stmt = $this->conn->prepare("DELETE FROM cthoadon WHERE MaHD = ?");
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        $stmt->close();

        // 3. Xoá hóa đơn
        $stmt = $this->conn->prepare("DELETE FROM hoadon WHERE MaHD = ?");
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        $stmt->close();
    }
}