<?php
session_start();
include "../../config/connect.php"; // đường dẫn đến file kết nối DB 

$email = $_POST['email'];
$password = $_POST['password'];

// Kiểm tra người dùng trong database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Giả sử mật khẩu được hash trong DB bằng password_hash
    if (password_verify($password, $user['password'])) {
        // Đăng nhập thành công
        $_SESSION['user'] = $user;
        header("Location: ../index.php"); // hoặc trang dashboard
        exit();
    } else {
        echo "Sai mật khẩu.";
    }
} else {
    echo "Email không tồn tại.";
}
?>
