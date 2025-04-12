<?php 
require_once('../Model/product_Model.php');
require_once('../../config/connect.php');
class product_Controller{
    
    private $product_model = null;
    public function __construct(){
        $this->product_model = new product_Model();
    }

    public function getAllProducts(){
       return $this->product_model->getAllProducts();
    }
}

?>
