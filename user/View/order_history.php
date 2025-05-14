<?php
session_start();
if (!isset($_SESSION['MaTK'])) {
    header("Location: ./user/view/login.php");
    exit();
}

$orders = $orders ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="/Shopquanao/public/user/css/order_history.css">
</head>
<body>
    <div class="order-history">
        <h2>Lịch sử đơn hàng</h2>

        <?php if (empty($orders)): ?>
            <p>Chưa có đơn hàng nào.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <p><strong>Mã hóa đơn:</strong> <?= $order['MaHD'] ?></p>
                    <p><strong>Thời gian:</strong> <?= $order['ThoiGian'] ?></p>
                    <p><strong>Trạng thái:</strong> <?= $order['TrangThai'] == 1 ? 'Chưa thanh toán' : 'Đã thanh toán' ?></p>
                    <p><strong>Phương thức thanh toán:</strong> <?= $order['ThanhToan'] ?></p>

                    <ul>
                        <?php foreach ($order['chitiet'] as $ct): ?>
                            <li><?= $ct['TenSP'] ?> (Size: <?= $ct['TenSize'] ?>) - SL: <?= $ct['SoLuongBan'] ?> - Giá: <?= number_format($ct['DonGia']) ?>đ</li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if ($order['TrangThai'] == 1): ?>
                        <form method="POST" action="index.php?page=cancel_order">
                            <input type="hidden" name="mahd" value="<?= $order['MaHD'] ?>">
                            <button type="submit" onclick="return confirm('Bạn có chắc muốn huỷ đơn hàng này?')">Huỷ đơn hàng</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>