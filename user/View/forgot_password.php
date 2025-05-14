<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Quên mật khẩu</h2>
        <form action="../index.php?page=reset_password_form.php" method="POST">
            <label for="email">Nhập email của bạn</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Gửi link reset mật khẩu</button>
        </form>
    </div>
</body>
</html>