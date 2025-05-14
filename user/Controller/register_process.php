<?php
include "../../config/connect.php"; // Kết nối DB
$db = new Database();
$conn = $db->connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {
        echo "<script>alert('Mật khẩu không khớp.'); window.history.back();</script>";
        exit();
    }

    // Kiểm tra email đã tồn tại trong bảng nguoidung
    $check_sql = "SELECT MaNguoiDung FROM nguoidung WHERE email = ?";
    if ($stmt = $conn->prepare($check_sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Email đã được sử dụng.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Lỗi kiểm tra email: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $maQuyen = 2; // Mặc định là quyền người dùng

    // Thêm vào bảng taikhoan
    $insert_sql = "INSERT INTO taikhoan (MaQuyen, TenTK, MatKhau, NgayTaoTK, TrangThai) VALUES (?, ?, ?, NOW(), 1)";
    if ($stmt = $conn->prepare($insert_sql)) {
        $stmt->bind_param("iss", $maQuyen, $email, $hashed_password);

        if ($stmt->execute()) {
            // Lấy mã tài khoản vừa tạo
            $maTK = $stmt->insert_id;

            // Thêm vào bảng nguoidung
            $insert_user_sql = "INSERT INTO nguoidung (MaNguoiDung, Email, MaLoai, TrangThai) VALUES (?, ?, 1, 1)";
            if ($stmt_user = $conn->prepare($insert_user_sql)) {
                $stmt_user->bind_param("is", $maTK, $email);
                $stmt_user->execute();
            }

            echo "<script>
                    alert('Đăng ký thành công! Bạn sẽ được chuyển đến trang đăng nhập.');
                    window.location.href = '../view/login.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Lỗi đăng ký: " . $stmt->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Lỗi SQL: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>