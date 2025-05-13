<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/Shopquanao/user/css/style.css">
    <script src="/Shopquanao/user/js/validate_login.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="login-section">
            <h2>Đăng nhập</h2>
            <div class="form-container">

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
                    unset($_SESSION['login_error']);
                }
                ?>

                <form id="loginForm" method="POST" action="/Shopquanao/user/Controller/login_process.php">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" class="login-btn">Đăng nhập</button>
                </form>

                <div class="register-link">
                    Chưa có tài khoản? <a href="/Shopquanao/user/View/register.php">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>