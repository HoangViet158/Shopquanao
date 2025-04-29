<?php 
require_once(__DIR__ . '/../Model/user_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
// header('Content-Type: application/json');
class user_Controller{
    
    private $user_model = null;
    public function __construct(){
        $this->user_model = new user_model();
    }
    public function getUSer($limit,$offset,$search){
        return $this->user_model->getUSer($limit,$offset,$search);
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

    public function editUser($MaTK, $TenTK, $DiaChi, $Email, $MaLoai, $MaQuyen) {
        return $this->user_model->editUser($MaTK, $TenTK, $DiaChi, $Email, $MaLoai, $MaQuyen);
    }

    public function deleteUser($MaTK) {
        return $this->user_model->deleteUser($MaTK);
    }
}