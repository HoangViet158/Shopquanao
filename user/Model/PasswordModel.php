<?php
include_once("../config/connect.php");

class PasswordModel {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connection();
    }

    // Tìm người dùng theo email
    public function findUserByEmail($email) {
        $query = "SELECT * FROM taikhoan WHERE TenTK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Lưu token vào bảng reset_password_tokens
    public function storeToken($maTK, $token) {
        $query = "INSERT INTO reset_password_tokens (MaTK, Token) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $maTK, $token);
        $stmt->execute();
    }

    public function getUserByEmail($email) {
    $sql = "SELECT MaTK FROM taikhoan WHERE TenTK = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    public function storeResetToken($maTK, $token) {
        $sql = "INSERT INTO reset_password_tokens (MaTK, token, created_at) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $maTK, $token);
        $stmt->execute();
    }
    // Kiểm tra token trong DB
    public function verifyToken($token) {
        $query = "SELECT * FROM reset_password_tokens WHERE Token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Cập nhật mật khẩu
    public function updatePassword($maTK, $newPassword) {
        $query = "UPDATE taikhoan SET MatKhau = ? WHERE MaTK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $newPassword, $maTK);
        $stmt->execute();
    }
}
?>