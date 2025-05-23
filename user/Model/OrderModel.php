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
    public function getUserPhone($userId)
    {
        $stmt = $this->conn->prepare("SELECT SoDienThoai FROM nguoidung WHERE MaNguoiDung = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['SoDienThoai'] : '';
    }
    public function createOrder($userId, $paymentMethod, $address, $phone)
{
    // 1. Cập nhật thông tin người dùng (địa chỉ và số điện thoại)
    $stmtUpdateUser = $this->conn->prepare("
        UPDATE nguoidung 
        SET DiaChi = ?, SoDienThoai = ?
        WHERE MaNguoiDung = ?
    ");
    $stmtUpdateUser->bind_param("ssi", $address, $phone, $userId);
    $stmtUpdateUser->execute();
    
    // 2. Tính tổng tiền
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

    // 3. Tạo hóa đơn
    $stmt = $this->conn->prepare("
        INSERT INTO hoadon (MaTK, ThoiGian, ThanhToan, MoTa, TrangThai)
        VALUES (?, NOW(), ?, ?, 0)
    ");
    $stmt->bind_param("ids", $userId, $totalPay, $paymentMethod);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // 4. Thêm chi tiết hóa đơn
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
        
        // 5. Cập nhật số lượng tồn kho
        $stmtUpdateAmount = $this->conn->prepare("
            UPDATE size_sanpham
            SET SoLuong = SoLuong - ?
            WHERE MaSP = ? AND MaSize = ?
        ");
        $stmtUpdateAmount->bind_param("iii", $item['SoLuong'], $item['MaSP'], $item['MaSize']);
        $stmtUpdateAmount->execute();
        $stmtUpdateSoLuongTongKho = $this->conn->prepare("
            UPDATE sanpham
            SET SoLuongTong = SoLuongTong - ?
            WHERE MaSP = ?
        ");
        $stmtUpdateSoLuongTongKho->bind_param("ii", $item['SoLuong'], $item['MaSP']);
        $stmtUpdateSoLuongTongKho->execute();

    }

    // 6. Xóa giỏ hàng
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
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT * FROM hoadon WHERE MaTK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderDetails($maHD)
    {
        $sql = "SELECT cthd.*, sp.TenSP, sz.TenSize 
                FROM cthoadon cthd 
                JOIN sanpham sp ON cthd.MaSP = sp.MaSP
                JOIN size sz ON cthd.MaSize = sz.MaSize
                WHERE cthd.MaHD = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHD);
        $stmt->execute();
        $result = $stmt->get_result();
        $a = [];
        while ($row = $result->fetch_assoc()){
            $a[] =  $row;
        }
        return $a;
    }

    public function cancelOrder($maHD, $userId)
    {
        // Kiểm tra trạng thái đơn hàng
        $sql = "SELECT TrangThai FROM hoadon WHERE MaHD = ? AND MaTK = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $maHD, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result || $result['TrangThai'] != 0) return false;

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
        $stmtIncrease = $this->conn->prepare("
            UPDATE sanpham
            SET SoLuongTong = SoLuongTong + ?
            WHERE MaSP = ?
        ");
        foreach ($items as $item) {
            $stmtIncrease->bind_param("ii", $item['SoLuongBan'], $item['MaSP']);
            $stmtIncrease->execute();
        }
        // Cập nhật trạng thái đơn
        $sql = "UPDATE hoadon SET TrangThai = 3 WHERE MaHD = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHD);
        return $stmt->execute();
    }
    public function getOrderDetail($orderId, $userId)
{
    // Lấy thông tin chính của đơn hàng
    $stmt = $this->conn->prepare("
        SELECT h.MaHD as id, h.ThoiGian as date, h.MoTa as payment_method, 
               h.ThanhToan as total, h.TrangThai as status,
               n.DiaChi as address, n.SoDienThoai as phone
        FROM hoadon h
        JOIN nguoidung n ON h.MaTK = n.MaNguoiDung
        WHERE h.MaHD = ? AND h.MaTK = ?
    ");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        return false;
    }

    // Lấy chi tiết sản phẩm trong đơn hàng
    $stmtItems = $this->conn->prepare("
        SELECT c.MaSP, s.TenSP, c.MaSize, sz.TenSize, 
               c.SoLuongBan, c.DonGia, c.ThanhTien
        FROM cthoadon c
        JOIN sanpham s ON c.MaSP = s.MaSP
        JOIN size sz ON c.MaSize = sz.MaSize
        WHERE c.MaHD = ?
    ");
    $stmtItems->bind_param("i", $orderId);
    $stmtItems->execute();
    $items = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);

    $result['items'] = $items;
    return $result;
}
}
