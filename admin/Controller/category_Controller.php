<?php
require_once("../../config/connect.php");
require_once("../../admin/Model/category_Model.php");
class category_Controller{
    private $category_Model=null;
    public function __construct()
    {
        return $this->category_Model= new category_Model();
    }
    public function getAllCategories(){
        return $this->category_Model->getAllCategories();
    }
}