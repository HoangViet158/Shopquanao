<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Đặt lại mật khẩu</h2>
        <form action="index.php?page=reset_password_form" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" />
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Cập nhật mật khẩu</button>
        </form>
    </div>
</body>
</html>