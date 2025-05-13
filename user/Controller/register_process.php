<?php
include "../../config/connect.php"; // Kết nối DB
$db=new Database();
$conn = $db->connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirm_password) {
        echo "Mật khẩu không khớp.";
        exit();
    }

    // Kiểm tra email đã tồn tại chưa
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email đã được sử dụng.";
        exit();
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Thêm người dùng vào DB
    $insert_sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Đăng ký thành công.";
        header("Location: login.php"); // Chuyển hướng đến trang login
        exit();
    } else {
        echo "Lỗi đăng ký: " . $stmt->error;
    }
}
?>
