<?php
require_once(__DIR__ . "/../../config/connect.php");
class promotion_Model{
    private $database =null;
    public function __construct()
    {
        $this->database=new Database();
    }
    public function getAllPromotions(){
        $sql="select * from khuyenmai where khuyenmai.TrangThai=1";
        $result=$this->database->execute($sql);
        $data=array();
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $data[]=array(
                    'MaKM' => $row['MaKM'],
                    'TenKM' => $row['TenKM'],
                    'TrangThai'=>$row['TrangThai'],
                    'NgayBatDau' =>$row['NgayBatDau'],
                    'NgayKetThuc' =>$row['NgayKetThuc'],
                    'giaTriKM' => $row['giaTriKM'],
                );
            }
        }
        return $data;
    }
}