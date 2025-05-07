<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form action="/login" method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Mật khẩu:</label>
        <input type="password" name="password" required>
        <button type="submit">Đăng nhập</button>
    </form>
    <p><a href="/forgot-password">Quên mật khẩu?</a></p>
    <p>Chưa có tài khoản? <a href="/register">Đăng ký</a></p>
</body>
</html>
