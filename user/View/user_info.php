<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../user/view/login.php");
    exit();
}
$db = new Database();
$conn = $db->connection();
// $maTK = $_SESSION['MaTK'];

// Lấy MaTK từ session
$maTK = $_SESSION['user']['id'];

// Sửa câu truy vấn để lấy dữ liệu từ cả hai bảng
$sql = "SELECT t.TenTK, n.Email, n.DiaChi
        FROM Taikhoan t
        JOIN Nguoidung n ON t.MaTK = n.MaNguoiDung
        WHERE t.MaTK = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Lỗi prepare: " . $conn->error); // In lỗi nếu prepare không thành công
}

$stmt->bind_param("i", $maTK);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Lấy thông tin từ truy vấn
    $tenNguoiDung = $user['TenTK'] ?? 'Người dùng';
    $email = $user['Email'] ?? 'Chưa có email';
    $diaChi = $user['DiaChi'] ?? 'Chưa có địa chỉ';
} else {
    // Nếu không tìm thấy người dùng
    $tenNguoiDung = 'Người dùng';
    $email = 'Chưa có email';
    $diaChi = 'Chưa có địa chỉ';
}

?>
<script>
    console.log("Tên người dùng: <?= htmlspecialchars($tenNguoiDung) ?>");
    
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thông tin người dùng</title>
    <link rel="stylesheet" href="/Shopquanao/public/user/css/user.css">
</head>
<body class="user-info-page">
    <div class="user-info-container">
        <h2>Thông tin tài khoản</h2>
        <p><strong>Tên người dùng:</strong> <?= htmlspecialchars($tenNguoiDung) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($diaChi) ?></p>
        <a href="logout.php">Đăng xuất</a>
    </div>
</body>
</html>