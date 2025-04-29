<?php 
require_once ('../../config/connect.php');
class user_model {
    private $database = null;
    public function __construct(){
        $this->database = new Database();
    }
    public function getUSer($limit,$offset,$search){
        $sql = "SELECT nguoidung.MaNguoiDung, 
                        nguoidung.DiaChi, 
                        nguoidung.Email, 
                        nguoidung.TrangThai,
                        nguoidung.MaLoai,
                        taikhoan.TenTK,
                        taikhoan.NgayTaoTK, 
                        taikhoan.MaQuyen    
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE taikhoan.TenTK LIKE CONCAT('%', ?, '%')
                ORDER BY taikhoan.NgayTaoTK ASC
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
                        nguoidung.TrangThai,
                        nguoidung.MaLoai,
                        taikhoan.TenTK,
                        taikhoan.NgayTaoTK, 
                        taikhoan.MaQuyen   
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE taikhoan.MaTK = ?";

        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        return $stmt->get_result();
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
    public function addUser($TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen) {
        $hashedPassword = password_hash($MatKhau, PASSWORD_DEFAULT);

        $stmt = $this->database->connection()->prepare("INSERT INTO taikhoan (MaQuyen, TenTK, MatKhau, NgayTaoTK, TrangThai) VALUES (?, ?, ?, NOW(), 1)");
        $stmt->bind_param('iss', $MaQuyen, $TenTK, $hashedPassword);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $MaTK = $this->database->connection()->insert_id;

            $stmt2 =  $this->database->connection()->prepare("INSERT INTO nguoidung (MaNguoiDung, DiaChi, Email, TrangThai, MaLoai) VALUES (?, ?, ?, 1, ?)");
            $stmt2->bind_param('issi', $MaTK, $DiaChi, $Email, $MaLoai);
            $stmt2->execute();

            return true;
        }
        return false;
    }

    // Sửa user
    public function editUser($MaTK, $TenTK, $DiaChi, $Email, $MaLoai, $MaQuyen) {
        $stmt =  $this->database->connection()->prepare("UPDATE taikhoan SET TenTK = ?, MaQuyen = ? WHERE MaTK = ?");
        $stmt->bind_param('sii', $TenTK, $MaLoai, $MaTK);
        $stmt->execute();

        $stmt = $this->database->connection()->prepare("UPDATE nguoidung SET DiaChi = ?, Email = ?, MaLoai = ? WHERE MaNguoiDung = ?");
        $stmt2->bind_param('ssii', $DiaChi, $Email, $MaLoai, $MaTK);
        $stmt2->execute();

        return true;
    }

    // Xóa user
    public function deleteUser($MaTK) {
       $stmt = $this->database->connection()->prepare("SELECT COUNT(*) FROM hoadon WHERE MaTK = ?");
        $check->bind_param('i', $MaTK);
        $check->execute();
        $check->bind_result($total);
        $check->fetch();
        $check->close();

        if ($total > 0) {
            return false;
        }

        $stmt = $this->database->connection()->prepare("DELETE FROM nguoidung WHERE MaNguoiDung = ?");
        $stmt1->bind_param('i', $MaTK);
        $stmt1->execute();

        $stmt2 = $this->database->prepare("DELETE FROM taikhoan WHERE MaTK = ?");
        $stmt2->bind_param('i', $MaTK);
        $stmt2->execute();

        return true;
    }
}
?>