<?php
include_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSP = $_POST['MaSP'] ?? '';
    $maSize = $_POST['MaSize'] ?? '';

    if ($maSP && $maSize) {
        $db = new Database();
        $conn = $db->connection();

        $sql = "DELETE FROM giohang WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $maSP, $maSize);

        echo $stmt->execute() ? "Đã xoá sản phẩm" : "Lỗi khi xoá";

        $stmt->close();
        $conn->close();
    } else {
        echo "Thiếu dữ liệu";
    }
}
?>