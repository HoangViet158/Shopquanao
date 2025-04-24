<?php
require_once(__DIR__ . "/../../config/connect.php");
require_once(__DIR__ . "/../../admin/Model/bill_Model.php");
class bill_Controller{
    private $billModel=null;
    public function __construct()
    {
        return $this->billModel=new bill_Model();
    }
    public function getAllBill(){
        return $this->billModel->getAllBill();
    }
    public function getAllBillDetail($MaHD){
        return $this->billModel->getAllBillDetail($MaHD);
    }
}