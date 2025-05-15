<?php
require_once(__DIR__ . '/../../config/connect.php');

class CartModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connection();
    }

    public function addCart($userId, $maSP, $maSize, $soLuong)
    {
        // Convert all parameters to integers
        $maSize = (int)$maSize;
        $maSP = (int)$maSP;
        $soLuong = (int)$soLuong;
        $userId = (int)$userId;

        // First check if item exists in cart
        $checkSql = "SELECT 1 FROM giohang WHERE MaNguoiDung = ? AND MaSP = ? AND MaSize = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param('iii', $userId, $maSP, $maSize);
        $checkStmt->execute();
        $exists = $checkStmt->get_result()->num_rows > 0;
        $checkStmt->close();

        if ($exists) {
            // Update existing item
            $sql = "UPDATE giohang SET SoLuong = SoLuong + ? 
                WHERE MaNguoiDung = ? AND MaSP = ? AND MaSize = ?";
        } else {
            // Insert new item
            $sql = "INSERT INTO giohang (MaNguoiDung, MaSP, MaSize, SoLuong, NgayThem) 
                VALUES (?, ?, ?, ?, NOW())";
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("SQL error: " . $this->conn->error);
        }

        if ($exists) {
            $stmt->bind_param('iiii', $soLuong, $userId, $maSP, $maSize);
        } else {
            $stmt->bind_param('iiii', $userId, $maSP, $maSize, $soLuong);
        }

        $success = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected > 0;
    }
    public function checkAmountAvaible($userId, $maSP, $maSize)
    {
        $sql = "Select SoLuong from giohang where MaNguoiDung = ? and MaSP = ? and MaSize = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $userId, $maSP, $maSize);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['SoLuong'];
        } else {
            return 0; // Hoặc giá trị mặc định khác nếu không tìm thấy
        }
    }

    public function getCartItems($userId)
    {

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

    public function deleteCartItem($userId, $maSP, $maSize)
    {
        $sql = "DELETE FROM giohang WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $maSP, $maSize, $userId);
        return $stmt->execute();
    }

    public function updateCartItem($userId, $maSP, $maSize, $soLuong)
    {
        $sql = "UPDATE giohang SET SoLuong = ? WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $soLuong, $maSP, $maSize, $userId);
        return $stmt->execute();
    }
    public function clearCart($userId)
    {
        $sql = "DELETE FROM giohang WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
