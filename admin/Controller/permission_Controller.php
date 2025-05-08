<?php 
require_once(__DIR__ . '/../Model/permission_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
class permission_Controller{
    
    private $permission_model = null;
    public function __construct(){
        $this->permission_model = new permission_Model();
    }
    public function getPermissionById($id){
        return $this->permission_model->getPermissionById($id);
    }
    public function getAllPermission(){
        return $this->permission_model->getAllPermission();
    }
    public function getAllType(){
        return $this->permission_model->getAllType();
    }

    public function getTypeById($id){
        return $this->permission_model->getTypeById($id);
    }

    public function addPermission($tenQuyen){
        return $this->permission_model->addPermission($tenQuyen);
    }

    public function deletePermission($id){
        return $this->permission_model->deletePermission($id);
    }

    public function isPermissionInUse($id){
        return $this->permission_model->isPermissionInUse($id);
    }

    public function getAllFunction(){
        return $this->permission_model->getAllFunction();
    }

    public function getAction($MaQuyen, $MaCTQ){
        return $this->permission_model->getAction($MaQuyen, $MaCTQ);
    }

    public function editPermissionDetail($MaQuyen, $MaCTQ, $action){
        return $this->permission_model->editPermissionDetail($MaQuyen, $MaCTQ, $action);
    }
}