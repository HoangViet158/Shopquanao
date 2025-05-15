<?php
require_once(__DIR__ . '/../../config/connect.php');

class CartModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function addCart($userId, $maSP, $maSize, $soLuong){
        $sql = "INSERT INTO giohang (MaNguoiDung, MaSP, MaSize, SoLuong, NgayThem) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iiii', $userId, $maSP, $maSize, $soLuong);
        return  $stmt->execute();
    }

    public function getCartItems($userId) {

        $sql = "SELECT gh.*, sp.TenSP, sp.GiaBan, sz.TenSize, sp.SoLuongTong, GROUP_CONCAT(a.URL) AS HinhAnh
                FROM giohang gh
                JOIN sanpham sp ON gh.MaSP = sp.MaSP
                JOIN size sz ON gh.MaSize = sz.MaSize
                LEFT JOIN anh a ON gh.MaSP = a.MaSP
                WHERE gh.MaNguoiDung = ?
                GROUP BY gh.MaGio, sp.TenSP, sp.GiaBan, sz.TenSize";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteCartItem($userId, $maSP, $maSize) {
        $sql = "DELETE FROM giohang WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $maSP, $maSize, $userId);
        return $stmt->execute();
    }

    public function updateCartItem($userId, $maSP, $maSize, $soLuong) {
        $sql = "UPDATE giohang SET SoLuong = ? WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $soLuong, $maSP, $maSize, $userId);
        return $stmt->execute();
    }
    public function clearCart($userId) {
        $sql = "DELETE FROM giohang WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
