<?php

require_once __DIR__ . '/../../config/connect.php';

class OrderModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function getCartItems($userId)
    {
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

    public function getUserAddress($userId)
    {
        $stmt = $this->conn->prepare("SELECT DiaChi FROM nguoidung WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['DiaChi'] : '';
    }

    public function createOrder($userId, $paymentMethod, $address, $phone)
    {
        $totalPay = 0;
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
        $stmt->bind_param("ids", $userId, $totalPay, $paymentMethod);
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
        $sqlInsertAddress = "insert into nguoidung(MaNguoiDung,DiaChi) values(?,?)";
        $stmt = $this->conn->prepare($sqlInsertAddress);
        $stmt->bind_param("is", $userId, $address);
        $stmt->execute();
        foreach ($cartItems as $item) {
            $stmtUpdateAmount = $this->conn->prepare("
                UPDATE size_sanpham
                SET SoLuong = SoLuong - ?
                WHERE MaSP = ? and MaSize = ?
            ");
        
            $stmtUpdateAmount->bind_param("iii", $item['SoLuong'], $item['MaSP'], $item['MaSize']);
            $stmtUpdateAmount->execute();
        }

        // Clear cart
        $stmt = $this->conn->prepare("DELETE FROM giohang WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $orderId;
    }
    public function checkAmountAvaible($masp, $masize, $soluong)
    {
        $stmt = $this->conn->prepare("
            SELECT SoLuong
            FROM size_sanpham
            WHERE MaSP = ? AND MaSize = ?
        ");
        $stmt->bind_param("ii", $masp, $masize);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['SoLuong'] >= $soluong : false;
    }
    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM hoadon WHERE MaTK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
    }

    public function getOrderDetails($maHD) {
        $sql = "SELECT cthd.*, sp.TenSP, sz.TenSize 
                FROM cthoadon cthd 
                JOIN sanpham sp ON cthd.MaSP = sp.MaSP
                JOIN size sz ON cthd.MaSize = sz.MaSize
                WHERE MaHD = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cancelOrder($maHD, $userId) {
        // Kiểm tra trạng thái đơn hàng
        $sql = "SELECT TrangThai FROM hoadon WHERE MaHD = ? AND MaTK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $maHD, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result || $result['TrangThai'] != 1) return false;

        // Lấy chi tiết đơn
        $sql = "SELECT MaSP, MaSize, SoLuongBan FROM cthoadon WHERE MaHD = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Tăng tồn kho lại
        foreach ($items as $item) {
            $sql = "UPDATE size_sanpham SET SoLuong = SoLuong + ? WHERE MaSP = ? AND MaSize = ?";
            $up = $this->conn->prepare($sql);
            $up->bind_param("iii", $item['SoLuongBan'], $item['MaSP'], $item['MaSize']);
            $up->execute();
        }

        // Cập nhật trạng thái đơn
        $sql = "UPDATE hoadon SET TrangThai = 3 WHERE MaHD = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHD);
        return $stmt->execute();
    }
}
