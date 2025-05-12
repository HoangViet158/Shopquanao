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
                <form action="#" method="POST">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm_password">Nhập lại mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit" class="register-btn">Đăng ký</button>
                </form>
                <div class="login-link">
                    Đã có tài khoản? <a href="login.php">Đăng nhập</a>
                </div>
            </div>
        </div>
</body>

</html>