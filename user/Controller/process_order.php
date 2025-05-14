<?php
include_once('./config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $paymentMethod = $_POST['payment_method'];
    $userId = 1; // Sử dụng mã người dùng thực tế

    // Insert vào bảng hóa đơn
    $sqlOrder = "INSERT INTO hoadon (MaTK, DiaChi, SoDienThoai, PhuongThucThanhToan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlOrder);
    $stmt->bind_param("isss", $userId, $address, $phone, $paymentMethod);
    $stmt->execute();

    // Lấy MaHD vừa tạo
    $orderId = $stmt->insert_id;

    // Insert vào bảng cthoadon
    $sqlCart = "SELECT * FROM giohang WHERE MaNguoiDung = ?";
    $stmtCart = $conn->prepare($sqlCart);
    $stmtCart->bind_param("i", $userId);
    $stmtCart->execute();
    $cartItems = $stmtCart->get_result();

    while ($item = $cartItems->fetch_assoc()) {
        $sqlDetail = "INSERT INTO cthoadon (MaHD, MaSP, SoLuongBan, DonGia, ThanhTien, MaSize) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtDetail = $conn->prepare($sqlDetail);
        $totalPrice = $item['GiaBan'] * $item['SoLuong'];
        $stmtDetail->bind_param("iiidii", $orderId, $item['MaSP'], $item['SoLuong'], $item['GiaBan'], $totalPrice, $item['MaSize']);
        $stmtDetail->execute();
    }

    // Xóa giỏ hàng của người dùng
    $sqlDeleteCart = "DELETE FROM giohang WHERE MaNguoiDung = ?";
    $stmtDeleteCart = $conn->prepare($sqlDeleteCart);
    $stmtDeleteCart->bind_param("i", $userId);
    $stmtDeleteCart->execute();

    // Chuyển hướng đến trang xác nhận
    header("Location: order_success.php");
    exit();
}
?>
