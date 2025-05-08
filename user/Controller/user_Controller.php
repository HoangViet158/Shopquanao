<?php 
require_once('../Model/user_Model.php');
require_once('../../config/connect.php');
class user_Controller{
    private $user_model = null;

    public function __construct(){
        $this->user_model = new user_Model();
    }

    public function getUserByUsername($username){
        return $this->user_model->getUserByUsername($username);
    }
}
    
?>