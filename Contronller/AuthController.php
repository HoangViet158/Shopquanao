<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Hàm đăng nhập
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Kiểm tra đăng nhập
            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user'] = $user; // Lưu thông tin người dùng vào session
                header('Location: /dashboard'); // Chuyển hướng đến trang quản lý
            } else {
                echo "Sai tài khoản hoặc mật khẩu!";
            }
        }
        require_once('views/auth/login.php'); // Hiển thị trang login
    }

    // Hàm đăng ký
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Đăng ký người dùng mới
            if ($this->userModel->register($name, $email, $password)) {
                echo "Đăng ký thành công!";
                header('Location: /login');
            } else {
                echo "Email đã tồn tại!";
            }
        }
        require_once('views/auth/register.php'); // Hiển thị trang đăng ký
    }

    // Hàm quên mật khẩu
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            // Gửi email để reset mật khẩu
            if ($this->userModel->forgotPassword($email)) {
                echo "Đã gửi email để reset mật khẩu!";
            } else {
                echo "Email không tồn tại!";
            }
        }
        require_once('views/auth/forgot_password.php'); // Hiển thị trang quên mật khẩu
    }
}
