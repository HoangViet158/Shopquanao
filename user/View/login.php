<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/validate_login.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="login-section">
            <h2>Đăng nhập</h2>
            <div class="form-container">
                <form id="loginForm" method="POST" action="#">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" class="login-btn">Đăng nhập</button>
                </form>
                <div class="register-link">
                    Chưa có tài khoản? <a href="register.php">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
