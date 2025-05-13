<?php require_once "../../user/View/header.php" ?>

<div class="container my-5">
    <div id="log-in">
        <img src="../../upload/products/imgmainlogin.jpg" alt="Hình ảnh">
        <div>
            <h2  class="text-center"> Đăng nhập </h2>
            <form action="/log-in" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên Người Dùng</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng " required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="text" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div>
                <div class="d-flex justify-content-center align-items-center mb-2">
                <button type="submit" name="login" class="btn btn-danger "> Đăng nhập </button>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                     Chưa có tài khoản? <a href="register.php" class="text-dark"><strong> Đăng ký </strong></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
