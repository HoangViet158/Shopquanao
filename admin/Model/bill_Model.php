<?php
require_once(__DIR__ . "/../../config/connect.php");
class bill_Model{
    private $database=null;
    function __construct()
    {
        return $this->database=new Database();
    }
    public function getAllBill(){
        $sql="SELECT hoadon.*, taikhoan.*, nguoidung.*, hoadon.TrangThai AS TrangThaiHoaDon
                FROM hoadon
                INNER JOIN taikhoan ON hoadon.MaTK = taikhoan.MaTK
                INNER JOIN nguoidung ON taikhoan.MaTK = nguoidung.MaNguoiDung";
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
                $TrangThai=$row['TrangThaiHoaDon'];
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
        $sql="select hoadon.*, cthoadon.*, sanpham.*,size.*, hoadon.TrangThai AS TrangThaiHoaDon from cthoadon
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
                $TrangThai=$row['TrangThaiHoaDon'];
                $data[]=array(
                    'MaHD' =>$MaHD,
                    'MaSP' =>$MaSP,
                    'TenSize' =>$MaSize,
                    'TenSP' =>$TenSP,
                    'SoLuongBan' => $SoLuongBan,
                    'DonGia' => $DonGia,
                    'ThanhTien' => $ThanhTien,
                    'TrangThai' =>$TrangThai
                );
            }
        }
        return $data;
    }
    public function updateBillStatus($billId, $newStatus){
        $sql="update hoadon set TrangThai=? where MaHD=?";
        $stmtUpdate=$this->database->prepare($sql);
        $stmtUpdate->bind_param('ii', $newStatus,$billId);
        return $stmtUpdate->execute();
    }
    public function filterBills($status = null, $fromDate = null, $toDate = null, $address = null) {
        $sql = "SELECT hoadon.*, taikhoan.*, nguoidung.*, hoadon.TrangThai AS TrangThaiHoaDon
                FROM hoadon
                INNER JOIN taikhoan ON hoadon.MaTK = taikhoan.MaTK
                INNER JOIN nguoidung ON taikhoan.MaTK = nguoidung.MaNguoiDung
                WHERE 1=1";
    
       
        if (!is_null($status)) {
            $sql .= " AND hoadon.TrangThai = '$status'";
        }
        if (!is_null($fromDate)) {
            $sql .= " AND hoadon.ThoiGian >= '$fromDate'";
        }
        if (!is_null($toDate)) {
            $sql .= " AND hoadon.ThoiGian <= '$toDate'";
        }
        if (!is_null($address)) {
            $sql .= " AND LOWER(nguoidung.DiaChi) COLLATE utf8mb4_bin LIKE '%$address%'";
        }
    
        $data = array();
        $result = $this->database->execute($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $MaHD = $row['MaHD'];
                $taikhoan = array(
                    'MaTK' => $row['MaTK'],
                    'TenTK' => $row['TenTK'],
                    'DiaChi' => $row['DiaChi'],
                );
                $ThanhToan = $row['ThanhToan'];
                $ThoiGian = $row['ThoiGian'];
                $TrangThai = $row['TrangThaiHoaDon'];
    
                $data[] = array(
                    'MaHD' => $MaHD,
                    'taikhoan' => $taikhoan,
                    'ThanhToan' => $ThanhToan,
                    'ThoiGian' => $ThoiGian,
                    'TrangThai' => $TrangThai,
                );
            }
        }
        return $data;
    }
    
}
