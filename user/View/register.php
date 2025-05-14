<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Cửa hàng quần áoo</title>
</head>

<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="register-section">
            <h2>Đăng ký tài khoản</h2>
            <div class="form-container">
                <form id="registerForm" action="../../user/controller/register_process.php" method="POST">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm_password">Nhập lại mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <div id="error" style="color:red; margin-top:10px;"></div>

                    <button type="submit" class="register-btn">Đăng ký</button>
                </form>
                <div class="login-link">
                    Đã có tài khoản? <a href="/Shopquanao/user/View/login.php">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gọi JavaScript kt đăng ký -->
    <script src="/Shopquanao/user/js/validate_register.js"></script>
</body>

</html>