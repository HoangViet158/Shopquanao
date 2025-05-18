<?php
include __DIR__ . '/header.php';

// Nếu có tham số GET xem chi tiết đơn
$detailOrderId = $_GET['detail'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="../../public/user/css/user.css">
</head>
<body class="user-info-page">
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <!-- Menu bên trái  -->
            <div class="list-group shadow-sm rounded">
                <a href="order_history.php" class="list-group-item list-group-item-action text-danger fw-bold">Quản lí đơn hàng</a>
                <a href="../View/user_info.php" class="list-group-item list-group-item-action text-danger fw-bold">Thông tin tài khoản</a>
                <a href="../View/change_password.php" class="list-group-item list-group-item-action text-danger fw-bold">Đổi mật khẩu</a>
                <a href="../View/logout.php" class="list-group-item list-group-item-action text-danger fw-bold">Đăng xuất</a>
            </div>
        </div>

        <div class="col-md-8">
            <form id="order_history-form" class="shadow-sm p-4 rounded bg-light" method="POST">
                <h4 class="mb-4 text-center text-danger">Quản lý đơn hàng</h4>

                <?php if (!$detailOrderId): ?>
                    <!-- Hiển thị danh sách đơn hàng -->
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order-summary border p-3 mb-3 rounded">
                                <p><strong>Mã đơn:</strong> <?= htmlspecialchars($order['MaHD']) ?></p>
                                <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($order['ThoiGian']) ?></p>
                                <p><strong>Trạng thái:</strong> 
                                    <?php
                                    switch ($order['TrangThai']) {
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
                                            echo '<span class="badge bg-danger">Đã hủy</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-dark">Không xác định</span>';
                                    }
                                    ?>
                                </p>
                                <div>
                                    <button type="button" class="btn btn-sm btn-primary btn-view-detail" data-id="<?= $order['MaHD'] ?>">
                                        <i class="bi bi-eye"></i> Xem chi tiết
                                    </button>

                                    <?php if ($order['TrangThai'] ==0): ?>
                                        <button type="button" class="btn btn-sm btn-danger btn-cancel-order" data-id="<?= $order['MaHD'] ?>">
                                            <i class="bi bi-trash"></i> Hủy đơn
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Chưa có đơn hàng nào.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Hiển thị chi tiết đơn hàng -->
                    <button type="button" id="btn-back" class="btn btn-outline-secondary mb-3">Quay lại danh sách</button>
                    <h5>Chi tiết đơn hàng #<?= htmlspecialchars($detailOrderId) ?></h5>
                    <?php if (!empty($detailOrder)): ?>
                    <table class="table table-bordered mt-3">
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
                            <?php foreach ($detailOrder as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['TenSP']) ?></td>
                                    <td><?= htmlspecialchars($item['TenSize']) ?></td>
                                    <td><?= $item['SoLuongBan'] ?></td>
                                    <td><?= number_format($item['DonGia']) ?>đ</td>
                                    <td><?= number_format($item['ThanhTien']) ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>Không tìm thấy chi tiết đơn hàng.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="../View/index.php" class="btn btn-outline-secondary">Thoát</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../js/order_history.js"></script>

</body>


</html>

