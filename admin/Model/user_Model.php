<?php 
require_once(__DIR__ . "/../../config/connect.php"); 
class user_model {
    private $database = null;
    public function __construct(){
        $this->database = new Database();
    }
    public function getUser($limit,$offset,$search){
        $sql = "SELECT nguoidung.MaNguoiDung, 
                        nguoidung.DiaChi, 
                        nguoidung.Email, 
                        nguoidung.SoDienThoai,
                        taikhoan.TrangThai,
                        nguoidung.MaLoai,
                        taikhoan.TenTK,
                        taikhoan.NgayTaoTK, 
                        taikhoan.MaQuyen    
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE taikhoan.TenTK LIKE CONCAT('%', ?, '%')
                ORDER BY nguoidung.MaNguoiDung ASC
                LIMIT $offset, $limit";  // <-- Chèn thẳng

        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param("s",$search);
        $stmt->execute();
        return $stmt->get_result();
    }
    // lấy user theo id
    public function getUserById($id){
        $sql = "SELECT nguoidung.MaNguoiDung, 
                        nguoidung.DiaChi, 
                        nguoidung.Email, 
                        nguoidung.SoDienThoai,
                        taikhoan.TrangThai,
                        nguoidung.MaLoai,
                        taikhoan.TenTK,
                        taikhoan.NgayTaoTK, 
                        taikhoan.MatKhau,
                        taikhoan.MaQuyen   
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE taikhoan.MaTK = ?";

        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPasswordById($id){
        $sql = "SELECT taikhoan.MatKhau
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE taikhoan.MaTK = ?";

        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tổng số lượng user
    public function getTotalUser($search){
        $sql = "SELECT COUNT(DISTINCT taikhoan.MaTK) as total
                FROM taikhoan
                WHERE taikhoan.TenTK LIKE CONCAT('%', ?, '%')";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('s',$search);
        $stmt->execute();
        return $stmt->get_result();
    }

    
    // Thêm user
    public function addUser($TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen, $SoDienThoai) {
        $hashedPassword = password_hash($MatKhau, PASSWORD_DEFAULT);

        $conn = $this->database->connection();
        $stmt = $conn->prepare("INSERT INTO taikhoan (MaQuyen, TenTK, MatKhau, NgayTaoTK, TrangThai) VALUES (?, ?, ?, NOW(), 1)");
        $stmt->bind_param('iss', $MaQuyen, $TenTK, $hashedPassword);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $MaTK = $conn->insert_id;

            $stmt2 =  $conn->prepare("INSERT INTO nguoidung (MaNguoiDung, DiaChi, Email, SoDienThoai, TrangThai, MaLoai) VALUES (?, ?, ?, ?, 1, ?)");
            $stmt2->bind_param('isssi', $MaTK, $DiaChi, $Email, $SoDienThoai, $MaLoai);
            $stmt2->execute();

            return true;
        }
        return false;
    }

    // Sửa user
    public function editUser($MaTK, $TenTK, $MatKhau ,$DiaChi, $Email, $MaLoai, $MaQuyen, $SoDienThoai) {
        $stmt =  $this->database->connection()->prepare("UPDATE taikhoan SET TenTK = ?,MatKhau = ?,MaQuyen = ? WHERE MaTK = ?");
        $stmt->bind_param('ssii', $TenTK,$MatKhau ,$MaQuyen, $MaTK);
        $stmt->execute();

        $stmt = $this->database->connection()->prepare("UPDATE nguoidung SET DiaChi = ?, Email = ?, SoDienThoai = ?, MaLoai = ? WHERE MaNguoiDung = ?");
        $stmt->bind_param('sssii', $DiaChi, $Email, $SoDienThoai, $MaLoai, $MaTK);
        $stmt->execute();

        return true;
    }

    // Xóa user
    public function deleteUser($TrangThai,$MaTK) {
        $sql = "UPDATE taikhoan SET TrangThai = ? WHERE MaTK = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('ii',$TrangThai,$MaTK);
        $stmt->execute();
        return true;
    }

    //Đổi mật khẩu user
    public function changePassword($MaTK, $MatKhau){
        $sql = "UPDATE taikhoan SET MatKhau = ? WHERE MaTK = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('si',$MatKhau, $MaTK);
        $stmt->execute();
        return true;
    }

    //Sửa thông tin cơ bản của user
    public function updateInformationUser($MaTK, $TenTK, $DiaChi){
        $stmt =  $this->database->connection()->prepare("UPDATE taikhoan SET TenTK = ? WHERE MaTK = ?");
        $stmt->bind_param('si', $TenTK, $MaTK);
        $stmt->execute();

        $stmt = $this->database->connection()->prepare("UPDATE nguoidung SET DiaChi = ? WHERE MaNguoiDung = ?");
        $stmt->bind_param('si', $DiaChi , $MaTK);
        $stmt->execute();

        return true;
    }
}
?>