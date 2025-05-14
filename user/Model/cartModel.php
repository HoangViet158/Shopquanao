<?php
include_once(__DIR__ . '/../../config/connect.php');

class CartModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->Connection();
    }

    public function getCartItems($userId) {
        $sql = "SELECT gh.*, sp.TenSP, sp.GiaBan, sz.TenSize, GROUP_CONCAT(a.URL) AS HinhAnh
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
}
?>