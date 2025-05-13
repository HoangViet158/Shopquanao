<?php
include_once(__DIR__ . '/../../config/connect.php');

class CartController {
    public function showCart($maNguoiDung) {
        $db = new Database();
        $conn = $db->connection();

        $sql = "SELECT gh.*, sp.TenSP, sp.GiaBan, sz.TenSize, szsp.SoLuong AS SoLuongTon,
                       (SELECT GROUP_CONCAT(URL) FROM anh WHERE MaSP = gh.MaSP) AS HinhAnh
                FROM giohang gh
                JOIN sanpham sp ON gh.MaSP = sp.MaSP
                JOIN size sz ON gh.MaSize = sz.MaSize
                JOIN size_sanpham szsp ON gh.MaSP = szsp.MaSP AND gh.MaSize = szsp.MaSize
                WHERE gh.MaNguoiDung = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $maNguoiDung);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();
        $conn->close();

        include_once('./View/cart.php');
    }
}