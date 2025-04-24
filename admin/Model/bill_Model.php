<?php
require_once(__DIR__ . "/../../config/connect.php");
class bill_Model{
    private $database=null;
    function __construct()
    {
        return $this->database=new Database();
    }
    public function getAllBill(){
        $sql="select * from hoadon
        inner join taikhoan on hoadon.MaTK=taikhoan.MaTK
        inner join nguoidung on taikhoan.MaTK=nguoidung.MaNguoiDung";
        $data=array();
        $result=$this->database->execute($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $MaHD=$row['MaHD'];
                $taikhoan=array(
                    'MaTK' => $row['MaTK'],
                    'TenTK'=> $row['TenTK'],
                    'DiaChi' => $row['DiaChi'],
                );
                
                $ThanhToan=$row['ThanhToan'];
                $ThoiGian=$row['ThoiGian'];
                $TrangThai=$row['TrangThai'];
                $data[]=array(
                    'MaHD' =>$MaHD,
                    'taikhoan'=>$taikhoan,
                    'ThanhToan' => $ThanhToan,
                    'ThoiGian' => $ThoiGian,
                    'TrangThai' => $TrangThai,
                );
            }
        }
        return $data;
    }
    public function getAllBillDetail($MaHD){
        $sql="select * from cthoadon
        inner join sanpham on cthoadon.MaSP=sanpham.MaSP
        inner join hoadon on cthoadon.MaHD=hoadon.MaHD
        INNER JOIN size ON cthoadon.MaSize=size.MaSize
        where  cthoadon.MaHD='$MaHD'
        ";
        $data=array();
        $result=$this->database->execute($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $MaHD=$row['MaHD'];
                $MaSP=$row['MaSP'];
                $TenSP=$row['TenSP'];
                $MaSize=$row['TenSize'];
                $SoLuongBan=$row['SoLuongBan'];
                $DonGia=$row['DonGia'];
                $ThanhTien=$row['ThanhTien'];
                $data[]=array(
                    'MaHD' =>$MaHD,
                    'MaSP' =>$MaSP,
                    'TenSize' =>$MaSize,
                    'TenSP' =>$TenSP,
                    'SoLuongBan' => $SoLuongBan,
                    'DonGia' => $DonGia,
                    'ThanhTien' => $ThanhTien,
                );
            }
        }
        return $data;
    }
}
