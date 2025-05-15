<?php include __DIR__ . '/header.php'; ?>
<?php

if (!isset($_SESSION['user'])) {
    header("Location: ../../user/View/login.php");
    exit();
}

if (isset($_SESSION['error'])) {
    echo '<div class="error">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

require_once(__DIR__ . '/../../user/Controller/OrderController.php');
$orderController = new OrderController();
$cartItems = $orderController->getCartItems($_SESSION['user']['id']);
$userAddress = $orderController->getUserAddress($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin đặt hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/user/css/order.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white d-flex justify-content-center align-items-center">
                        <h2 class="h4 mb-0 gg ">Thông tin đặt hàng</h2>
                    </div>

                    <div class="card-body">
                        <form id="order-form" action="../../user/API/cart_api.php?type=createOrder" method="POST">
                            <div class="mb-3">
                                <label for="diachi" class="form-label">Địa chỉ nhận hàng</label>
                                <input type="text" class="form-control" id="diachi" name="diachi"
                                    value="<?= htmlspecialchars($userAddress) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="sdt" class="form-label">Số điện thoại</label>
                                <input type="number" class="form-control" id="sdt" name="sdt" required>
                            </div>

                            <div class="mb-4">
                                <label for="thanhtoan" class="form-label">Phương thức thanh toán</label>
                                <select class="form-select" id="thanhtoan" name="thanhtoan" required>
                                    <option value="Thanh toán khi nhận hàng">Thanh toán khi nhận hàng</option>
                                    <option value="Chuyển khoản">Chuyển khoản</option>
                                </select>
                            </div>

                            <h4 class="mb-3">Sản phẩm trong giỏ hàng</h4>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Size</th>
                                            <th>Số lượng</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($cartItems)): ?>
                                            <?php foreach ($cartItems as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['TenSP']) ?></td>
                                                    <td><?= htmlspecialchars($item['TenSize']) ?></td>
                                                    <td><?= $item['SoLuong'] ?></td>
                                                    <td><?= number_format($item['GiaBan'], 0, ',', '.') ?>đ</td>
                                                    <td><?= number_format($item['GiaBan'] * $item['SoLuong'], 0, ',', '.') ?>đ</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Giỏ hàng trống</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">Xác nhận đặt hàng</button>
                                <a href="../../user/view/product.php" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#order-form').on('submit', function(e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);

            $.ajax({
                url: '../../user/API/index.php?type=createOrder',
                type: 'POST',
                data: formData,
                processData: false, // Không xử lý dữ liệu k có 2 dòng này lỗi jquery
                contentType: false, // Không đặt header Content-Type
                success: function(response) {
                    try {
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        if (data.success) {
                            alert('Đặt hàng thành công');
                            window.location.href = `../../user/View/product.php`;
                        } else {
                            alert(data.error || 'Đặt hàng không thành công');
                        }
                    } catch (e) {
                        alert('Phản hồi không hợp lệ từ máy chủ');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi:', error);
                    alert('Có lỗi xảy ra khi đặt hàng');
                }
            });
        });
    </script>
    <script src="../../public/user/js/products.js"></script>
</body>
<?php include __DIR__ . '/footer.php'; ?>

</html>