<?php
include __DIR__ . '/header.php';
// session_start();
require_once dirname(__DIR__, 2) . '/config/connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../user/view/login.php");
    exit();
}
$db = new Database();
$conn = $db->connection();
$maTK = $_SESSION['user']['id'];

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
    <link rel="stylesheet" href="../../public/user/css/user.css">
</head>
<body class="user-info-page">
    <div class="container my-5">
        <div class="row">
            <!-- Bên trái: menu -->
            <div class="col-md-4">
                <div class="list-group shadow-sm rounded">
                    <a href="#" class="list-group-item list-group-item-action text-danger fw-bold">Quản lí đơn hàng</a>
                    <a href="user_info.php" class="list-group-item list-group-item-action text-danger fw-bold">Thông tin tài khoản</a>
                    <a href="change_password.php" class="list-group-item list-group-item-action text-danger fw-bold">Đổi mật khẩu</a>
                    <a href="logout.php" class="list-group-item list-group-item-action text-danger fw-bold">Đăng xuất</a>
                </div>
            </div>

            <!-- Bên phải: form -->
            <div class="col-md-8">
                <form id="user-information-form" method="POST" class="shadow-sm p-4 rounded bg-light">
                    <h4 class="mb-4 text-center text-danger">Cập nhật thông tin</h4>
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= htmlspecialchars($tenNguoiDung) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($email) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?= htmlspecialchars($diaChi) ?>" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">Thoát</a>
                        <button type="submit" class="btn btn-danger">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="../../public/user/js/update-user.js"></script>
</body>
</html>