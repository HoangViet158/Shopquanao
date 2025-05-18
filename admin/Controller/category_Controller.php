<?php
require_once(__DIR__ . "/../../config/connect.php");
require_once(__DIR__ . "/../../admin/Model/category_Model.php");
class category_Controller{
    private $category_Model=null;
    public function __construct()
    {
        return $this->category_Model= new category_Model();
    }
    public function getAllCategories(){
        return $this->category_Model->getAllCategories();
    }
    public function getAllTypeByCategory($id){
        return $this->category_Model->getAllTypeByCategory($id);
    }
}