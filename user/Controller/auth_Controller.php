<?php 
require_once(__DIR__ . '/../Model/auth_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
class auth_Controller{
    private $auth_model = null;

    public function __construct(){
        $this->auth_model = new auth_Model();
    }

    public function getAuthByEmail($email){
        return $this->auth_model->getAuthByEmail($email);
    }

    public function loginValidate($email,$matkhau){
        return $this->auth_model->login($email,$matkhau);
    }

}
    
?>