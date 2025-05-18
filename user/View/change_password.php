<?php
include __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="../../public/user/css/user.css">
</head>

<body class="user-info-page">
    <div class="container my-5">
        <div class="row">
            <!-- Bên trái: menu -->
            <div class="col-md-4">
                <div class="list-group shadow-sm rounded">
                    <a href="../Ajax/order_history.php" class="list-group-item list-group-item-action text-danger fw-bold">Quản lí đơn hàng</a>
                    <a href="user_info.php" class="list-group-item list-group-item-action text-danger fw-bold">Thông tin tài khoản</a>
                    <a href="change_password.php" class="list-group-item list-group-item-action text-danger fw-bold">Đổi mật khẩu</a>
                    <a href="logout.php" class="list-group-item list-group-item-action text-danger fw-bold">Đăng xuất</a>
                </div>
            </div>

            <!-- Bên phải: form -->
            <div class="col-md-8">
                <form id="change-password-form" class="shadow-sm p-4 rounded bg-light" method="POST">
                    <h4 class="mb-4 text-center text-danger"> Đổi mật khẩu </h4>
                    <div class="mb-3">
                        <label for="password-old" class="form-label">Mật khẩu cũ </label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="*********" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="password-new" class="form-label"> Mật khẩu mới </label>
                        <input type="password" class="form-control" id="password-new" name="password-new"
                            placeholder="Nhập mật khẩu mới" requried>
                    </div>
                    <div class="mb-3">
                        <label for="password-new-confirm" class="form-label"> Nhập lại mật khẩu mới </label>
                        <input type="text" class="form-control" id="password-new-confirm" name="password-new-confirm"
                            placeholder="Nhập lại mật khẩu mới" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">Thoát</a>
                        <button type="submit" class="btn btn-danger">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../public/user/js/change-password.js"></script>
    <script src="/Shopquanao/public/user/js/products.js"></script>
</body>

</html>