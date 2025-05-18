<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../public/user/css/user.css" />
    <title>Shop Quần áo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div id="log-in">
        <img src="../../upload/products/imgmainlogin.jpg" alt="Hình ảnh">
        <div>
            <h2  class="text-center"> Đăng nhập </h2>
            <form id="login-form" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Nhập tên người dùng " required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div>
                <div class="d-flex justify-content-center align-items-center mb-2">
                <button type="submit" name="login" class="btn btn-danger "> Đăng nhập </button>
                </div>
                <!-- <div class="d-flex justify-content-center align-items-center">
                     Chưa có tài khoản? <a href="register.php" class="text-dark"><strong> Đăng ký </strong></a>
                </div> -->
            </div>
        </div>
    </div>
</body>
</html>
<script src="../../public/admin/js/auth.js"></script>