<?php require_once "../../user/View/header.php" ?>

<div class="container my-5">
    <div id="log-in">
        <img src="../../upload/products/imgmainlogin.jpg" alt="Hình ảnh">
        <div>
            <h2  class="text-center"> Đăng ký </h2>
            <form action="/log-in" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên Người Dùng</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên người dùng " required>
                </div>
                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="text" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Mật khẩu</label>
                    <input type="text" class="form-control" id="password-confirm" name="password-confirm" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div>
                <div class="d-flex justify-content-center align-items-center mb-2">
                <button type="submit" name="register" class="btn btn-danger "> Đăng ký </button>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                     Đã có tài khoản? <a href="login.php" class="text-dark"><strong> Đăng nhập </strong></a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "../../user/View/footer.php" ?>