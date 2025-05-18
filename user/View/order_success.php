<?php
include __DIR__ . '/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? 0;
require_once(__DIR__ . '/../../user/Controller/OrderController.php');
$orderController = new OrderController();
$order = $orderController->getOrderDetail($order_id, $_SESSION['user']['id']);

if (!$order) {
    header("Location: product.php");
    exit();
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h2 class="h4">Hóa đơn #<?= $order['id'] ?></h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="text-success">Đặt hàng thành công!</h3>
                        <p>Cảm ơn bạn đã đặt hàng tại cửa hàng chúng tôi</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin khách hàng</h5>
                            <p>Tên: <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                            <p>Địa chỉ: <?= htmlspecialchars($order['address']) ?></p>
                            <p>SĐT: <?= htmlspecialchars($order['phone']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin đơn hàng</h5>
                            <p>Mã đơn: #<?= $order['id'] ?></p>
                            <p>Ngày đặt: <?= $order['date'] ?></p>
                            <p>PT thanh toán: <?= htmlspecialchars($order['payment_method']) ?></p>
                            <p>Trạng thái: 
                                    <?php
                                    switch ($order['status']) {
                                        case 0:
                                            echo '<span class="badge bg-warning text-dark">Đang chờ xác nhận</span>';
                                            break;
                                        case 1:
                                            echo '<span class="badge bg-primary">Đã xác nhận</span>';
                                            break;
                                        case 2:
                                            echo '<span class="badge bg-success">Đang giao hàng</span>';
                                            break;
                                        case 3:
                                            echo '<span class="badge bg-secondary">Đã hủy</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-dark">Không xác định</span>';
                                    }
                                    ?>
                            </p> 
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Chi tiết đơn hàng</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Size</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['TenSP']) ?></td>
                                    <td><?= htmlspecialchars($item['TenSize']) ?></td>
                                    <td><?= $item['SoLuongBan'] ?></td>
                                    <td><?= number_format($item['DonGia'], 0, ',', '.') ?>đ</td>
                                    <td><?= number_format($item['ThanhTien'], 0, ',', '.') ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <h5>Tổng cộng: <?= number_format($order['total'], 0, ',', '.') ?>đ</h5>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="product.php" class="btn btn-danger">Tiếp tục mua sắm</a>
                        <a href="../Ajax/order_history.php" class="btn btn-outline-secondary">Xem tất cả đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>