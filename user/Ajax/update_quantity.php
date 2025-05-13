<?php
include_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSP = $_POST['MaSP'] ?? '';
    $maSize = $_POST['MaSize'] ?? '';
    $soLuong = $_POST['SoLuong'] ?? '';

    if ($maSP && $maSize && $soLuong) {
        $db = new Database();
        $conn = $db->connection();

        $sql = "UPDATE giohang SET SoLuong = ? WHERE MaSP = ? AND MaSize = ? AND MaNguoiDung = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $soLuong, $maSP, $maSize);

        echo $stmt->execute() ? "Cập nhật thành công" : "Lỗi khi cập nhật";

        $stmt->close();
        $conn->close();
    } else {
        echo "Thiếu dữ liệu";
    }
}
?>