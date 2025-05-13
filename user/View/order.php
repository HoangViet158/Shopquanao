<?php
session_start();
if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: /Shopquanao/user/View/login.php");
    exit();
}
?>

<h2>Thông tin đặt hàng</h2>
<form action="?page=order_process" method="POST">
    <label>Địa chỉ nhận hàng:</label>
    <input type="text" name="diachi" required><br>

    <label>Số điện thoại:</label>
    <input type="text" name="sdt" required><br>

    <label>Phương thức thanh toán:</label>
    <select name="thanhtoan" required>
        <option value="Thanh toán khi nhận hàng">Thanh toán khi nhận hàng</option>
        <option value="Chuyển khoản">Chuyển khoản</option>
    </select><br>

    <button type="submit">Xác nhận đặt hàng</button>
</form>

<h3>Sản phẩm trong giỏ hàng:</h3>
<ul>
    <?php foreach ($cartItems as $item): ?>
        <li><?= $item['TenSize'] ?> - SL: <?= $item['SoLuong'] ?> - Giá: <?= number_format($item['GiaBan']) ?> VNĐ</li>
    <?php endforeach; ?>
</ul>