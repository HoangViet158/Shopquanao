<?php
require_once("../../config/connect.php");
require_once("../../admin/Model/promotion_Model.php");
class promotion_Controller{
    private $promotion_model=null;
    public function __construct()
    {
        $this->promotion_model=new promotion_Model();
    }
    public function getAllPromotions(){
        return $this->promotion_model->getAllPromotions();
    }
}