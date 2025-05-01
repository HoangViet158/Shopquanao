<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Kết nối với cơ sở dữ liệu
    }

    // Hàm đăng nhập
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công
                return $user; // Trả về thông tin người dùng
            }
        }
        return false; // Sai tài khoản hoặc mật khẩu
    }

    // Hàm đăng ký
    public function register($name, $email, $password) {
        // Kiểm tra xem email đã tồn tại chưa
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false; // Email đã tồn tại
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng mới
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        return $stmt->execute();
    }

    // Hàm quên mật khẩu
    public function forgotPassword($email) {
        // Kiểm tra email có tồn tại không
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Gửi email để reset mật khẩu (Ví dụ: gửi link reset mật khẩu)
            return true; // Đã gửi email
        }
        return false; // Email không tồn tại
    }
}
