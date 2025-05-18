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
$userPhone = $orderController->getUserPhone($_SESSION['user']['id']);
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
                        <form id="order-form"  method="POST">
                            <div class="mb-3">
                                <label for="diachi" class="form-label">Địa chỉ nhận hàng</label>
                                <input type="text" class="form-control" id="diachi" name="diachi"
                                    value="<?= htmlspecialchars($userAddress) ?>" required readonly>
                                <button type="button" class="btn btn-link">Thay đổi địa chỉ</button>
                            </div>

                            <div class="mb-3">
                                <label for="sdt" class="form-label">Số điện thoại</label>
                                <input type="number" class="form-control" id="sdt" name="sdt"
                                    value="<?= htmlspecialchars($userPhone) ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="thanhtoan" class="form-label">Phương thức thanh toán</label>
                                <select class="form-select" id="thanhtoan" name="thanhtoan" required>
                                    <option value="Thanh toán khi nhận hàng">Thanh toán khi nhận hàng</option>
                                    <option value="Thẻ ATM">Thẻ ATM</option>
                                </select>
                            </div>
                            <div id="bank-info" class="mb-3" style="display: none;">
                                <label for="bankNumber" class="form-label">Số thẻ ngân hàng</label>
                                <input type="text" class="form-control" id="bankNumber" name="bankNumber" placeholder="Nhập số thẻ" >

                                <label for="bankName" class="form-label mt-2">Ngân hàng</label>
                                <select class="form-select" id="bankName" name="bankName">
                                    <option value="">-- Chọn ngân hàng --</option>
                                    <option value="Vietcombank">Vietcombank</option>
                                    <option value="Techcombank">Techcombank</option>
                                    <option value="MB Bank">MB Bank</option>
                                    <option value="VietinBank">VietinBank</option>
                                    <option value="Agribank">Agribank</option>
                                </select>
                            </div>
                            <!-- Modal hóa đơn -->
                            <div class="modal fade" id="billModal" tabindex="-1" aria-labelledby="billModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Xác nhận hóa đơn thanh toán</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="billContent"></div>
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="confirmCheck">
                                                <label class="form-check-label" for="confirmCheck">
                                                    Tôi đã đọc và kiểm tra trước khi thanh toán.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="button" class="btn btn-danger" id="confirmPaymentBtn">Xác nhận thanh toán</button>
                                        </div>
                                    </div>
                                </div>
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
                                <button type="submit" class="btn btn-danger btn-lg">Thanh toán</button>
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
        $(document).ready(function() {
            // Xử lý sự kiện khi nhấn nút "Thay đổi địa chỉ"
            $('.btn-link').on('click', function() {
                var newAddress = prompt("Nhập địa chỉ mới:");
                if (newAddress) {
                    $('#diachi').val(newAddress);
                }
            });
        });
        $('#thanhtoan').on('change', function() {
            if ($(this).val() === 'Thẻ ATM') {
                $('#bank-info').show();
            } else {
                $('#bank-info').hide();
            }
        });

        // Khi submit form, chặn lại và hiển thị bill
        // Khi submit form, chặn lại và hiển thị bill
        $('#order-form').on('submit', function(e) {
            e.preventDefault();

            const diachi = $('#diachi').val();
            const sdt = $('#sdt').val();
            const thanhtoan = $('#thanhtoan').val();
            const bankNumber = $('#bankNumber').val();
            const bankName = $('#bankName').val();

            const spHtml = <?php echo json_encode($cartItems); ?>;
            let total = 0;

            // Tạo bảng sản phẩm
            let productTable = `
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
            <tbody>`;

            spHtml.forEach(sp => {
                const thanhTien = sp.GiaBan * sp.SoLuong;
                total += thanhTien;
                productTable += `
            <tr>
                <td>${sp.TenSP}</td>
                <td>${sp.TenSize}</td>
                <td>${sp.SoLuong}</td>
                <td>${Number(sp.GiaBan).toLocaleString()}đ</td>
                <td>${Number(thanhTien).toLocaleString()}đ</td>
            </tr>`;
            });

            productTable += `
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Tổng cộng:</th>
                    <th>${Number(total).toLocaleString()}đ</th>
                </tr>
            </tfoot>
        </table>`;

            const paymentNote = thanhtoan === 'Thẻ ATM' ?
                `Thanh toán bằng thẻ ngân hàng (${bankName} - ${bankNumber})` :
                `Thanh toán khi nhận hàng`;

            const html = `
        <div class="row">
            <div class="col-md-6">
                <h5>Thông tin khách hàng</h5>
                <p><strong>Địa chỉ:</strong> ${diachi}</p>
                <p><strong>Số điện thoại:</strong> ${sdt}</p>
                <p><strong>Email:</strong> <?= $_SESSION['user']['email'] ?? 'Không có' ?></p>
            </div>
            <div class="col-md-6">
                <h5>Thông tin đơn hàng</h5>
                <p><strong>Ngày đặt:</strong> ${new Date().toLocaleDateString()}</p>
                <p><strong>Phương thức:</strong> ${thanhtoan}</p>
            </div>
        </div>
        
        <h5 class="mt-4">Chi tiết đơn hàng</h5>
        ${productTable}
        
        <div class="alert alert-info mt-3">
            <strong>Ghi chú:</strong> ${paymentNote}
        </div>`;

            $('#billContent').html(html);
            const modal = new bootstrap.Modal(document.getElementById('billModal'));
            modal.show();
        });

        // Khi click "Xác nhận thanh toán"
        $('#confirmPaymentBtn').on('click', function() {
            if (!$('#confirmCheck').is(':checked')) {
                alert('Vui lòng xác nhận trước khi thanh toán.');
                return;
            }

            // Gửi form qua AJAX
            const form = $('#order-form')[0];
            const formData = new FormData(form);

            $.ajax({
                url: '../../user/API/index.php?type=createOrder',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;
                        if (data.success) {
                            Swal.fire({
                                title: 'Đặt hàng thành công!',
                                text: 'Cảm ơn bạn đã đặt hàng.',
                                icon: 'success',
                            });
                            window.location.href = `order_success.php?order_id=${data.order_id}`;
                        } else {
                            alert(data.error || 'Đặt hàng không thành công');
                        }
                    } catch (e) {
                        alert('Phản hồi không hợp lệ từ máy chủ');
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi gửi đơn hàng');
                }
            });
        });
    </script>
    <script src="../../public/user/js/products.js"></script>
</body>
<?php include __DIR__ . '/footer.php'; ?>

</html>