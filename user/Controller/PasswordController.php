<?php
include_once("../Model/PasswordModel.php");

class PasswordController {

    public function showPasswordForm() {
        include("../View/forgot_password.php");
    }

    public function processPasswordReset() {
        include_once (__DIR__ .'../Model/PasswordModel.php');
        $model = new PasswordModel();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $user = $model->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(16));
                $model->storeResetToken($user['MaTK'], $token);

                $link = "http://localhost/Shopquanao/user/index.php?page=reset_password&token=$token";
                mail($email, "Đặt lại mật khẩu", "Nhấn vào liên kết sau để đặt lại mật khẩu: $link");

                echo "<script>alert('Đã gửi link đặt lại mật khẩu.'); window.location.href='index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Email không tồn tại'); history.back();</script>";
                exit();
            }
        }
    }

    public function resetPassword() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $passwordModel = new PasswordModel();
            $user = $passwordModel->verifyToken($token);
            if ($user) {
                // Hiển thị form để nhập mật khẩu mới
                include("../View/reset_password.php");
            } else {
                echo "Token không hợp lệ.";
            }
        }
    }

    public function updatePassword() {
        if (isset($_POST['new_password']) && isset($_POST['token'])) {
            $newPassword = $_POST['new_password'];
            $token = $_POST['token'];
            $passwordModel = new PasswordModel();
            $user = $passwordModel->verifyToken($token);
            if ($user) {
                // Mã hóa mật khẩu mới và cập nhật vào DB
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $passwordModel->updatePassword($user['MaTK'], $hashedPassword);
                echo "Mật khẩu đã được thay đổi thành công.";
            } else {
                echo "Token không hợp lệ.";
            }
        }
    }
}
?>