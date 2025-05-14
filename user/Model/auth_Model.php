<?php
require_once("../../config/connect.php");
class auth_Model{
    private $database =null;
    public function __construct()
    {
        $this->database=new Database();
    }
    function getUserByEmail($email){
        $sql = "SELECT nguoidung.MaNguoiDung, 
                        nguoidung.DiaChi, 
                        nguoidung.Email, 
                        taikhoan.TrangThai,
                        nguoidung.MaLoai,
                        taikhoan.TenTK,
                        taikhoan.NgayTaoTK, 
                        taikhoan.MatKhau,
                        taikhoan.MaQuyen   
                FROM nguoidung 
                JOIN taikhoan ON nguoidung.MaNguoiDung = taikhoan.MaTK
                WHERE nguoidung.Email = ?";
        
        $stmt = $this->database->prepare($sql);

        $stmt->bind_param("s",$email);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function login($email,$matkhau){
        $user = $this->getUserByEmail($email);
        if($user){
            if(password_verify($matkhau, $user['MatKhau'])){
                return $user;
            }
            else{
                return false;
            }
        }
        return false;
    }
}
?>