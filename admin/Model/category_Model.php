<?php
require_once(__DIR__ . '/../../config/connect.php');
class category_Model{
    private $database=null;
    public function __construct()
    {
        return $this->database=new Database();
    }
    public function getAllCategories(){
        $sql= "select * from danhmuc where danhmuc.TrangThai=1";
        $result=$this->database->execute($sql);
        $data=array();
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $data[]=array(
                    'MaDM' => $row['MaDM'],
                    'TenDM' =>$row['TenDM'],
                    'TrangThai' =>$row['TrangThai'],
                );
            }
        }
        return $data;
    }
}