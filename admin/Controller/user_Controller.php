<?php 
require_once(__DIR__ . '/../Model/user_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
// header('Content-Type: application/json');
class user_Controller{
    
    private $user_model = null;
    public function __construct(){
        $this->user_model = new user_model();
    }
    public function getUser($limit,$offset,$search){
        return $this->user_model->getUser($limit,$offset,$search);
    }

    public function getUserById($id){
        return $this->user_model->getUserById($id);
    }

    public function getTotalUser($search){
        return $this->user_model->getTotalUser($search);
    }
    public function addUser($TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen) {
        return $this->user_model->addUser($TenTK, $MatKhau, $DiaChi, $Email, $MaLoai, $MaQuyen);
    }

    public function editUser($MaTK, $TenTK,$MatKhau ,$DiaChi, $Email, $MaLoai, $MaQuyen) {
        if (!empty(trim($MatKhau))) {
            // Có mật khẩu mới: hash và cập nhật
            $hashedPassword = password_hash($MatKhau, PASSWORD_DEFAULT);
        } else {
            // Không nhập gì → dùng lại mật khẩu cũ từ DB
            $hashedPassword = $this->user_model->getPasswordById($MaTK)['MatKhau'];
        }
        return $this->user_model->editUser($MaTK, $TenTK, $hashedPassword ,$DiaChi, $Email, $MaLoai, $MaQuyen);
    }

    public function deleteUser($TrangThai,$MaTK) {
        return $this->user_model->deleteUser($TrangThai,$MaTK);
    }

    public function changePassword($MaTK, $MatKhau){
        if(!empty(trim($MatKhau))){
            $hashedPassword = password_hash($MatKhau, PASSWORD_DEFAULT);   
        } else {
            return false;
        }
        return $this->user_model->changePassword($MaTK, $hashedPassword);
    }

    public function updateInformationUser($MaTK, $TenTK, $DiaChi){
        return $this->user_model->updateInformationUser($MaTK, $TenTK, $DiaChi);
    }
}