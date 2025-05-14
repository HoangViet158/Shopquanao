<?php
session_start();
include "../../config/connect.php"; // Kết nối CSDL

$db = new Database();
$conn = $db->connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Truy vấn tài khoản theo email từ bảng nguoidung + kiểm tra trạng thái
    $sql = "SELECT tk.MaTK, tk.MatKhau FROM taikhoan tk 
            JOIN nguoidung nd ON tk.MaTK = nd.MaNguoiDung 
            WHERE nd.Email = ? AND tk.TrangThai = 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if (password_verify($password, $row['MatKhau'])) {
                $_SESSION['MaTK'] = $row['MaTK'];
                header("Location: ../index.php"); // Chuyển về trang chính
                exit();
            } else {
                $_SESSION['login_error'] = "Sai mật khẩu.";
            }
        } else {
            $_SESSION['login_error'] = "Email không tồn tại hoặc tài khoản bị khóa.";
        }
    } else {
        $_SESSION['login_error'] = "Lỗi truy vấn: " . $conn->error;
    }

    // Nếu có lỗi, quay lại login
    header("Location: ../View/login.php");
    exit();
}
?>