<?php
require_once("../../config/connect.php");
class product_Model
{
    private $database = null;
    public function __construct()
    {
        $this->database = new Database();
    }
    public function getAllProducts()
    {
        $sql = "SELECT * FROM sanpham 
            LEFT JOIN khuyenmai ON sanpham.MaKM = khuyenmai.MaKM
            LEFT JOIN danhmuc ON sanpham.MaDM = danhmuc.MaDM
            WHERE sanpham.TrangThai = 1";

        $result = $this->database->execute($sql);
        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $maSP = $row['MaSP'];
                $sql_anh = "SELECT Url FROM anh WHERE MaSP = '$maSP'";
                $result_anh = $this->database->execute($sql_anh);
                $anh = array();
                if ($result_anh && $result_anh->num_rows > 0) {
                    while ($row_anh = $result_anh->fetch_assoc()) {
                        $anh[] = $row_anh['Url'];
                    }
                }

                $khuyenmai = array(
                    'MaKM' => $row['MaKM'],
                    'TenKM' => $row['TenKM'] ?? 'Không có',
                );
                $danhmuc = array(
                    'MaDM' => $row['MaDM'],
                    'TenDM' => $row['TenDM'] ?? 'Không có',
                );

                $data[] = array(
                    'MaSP' => $row['MaSP'],
                    'TenSP' => $row['TenSP'],
                    'MoTa' => $row['MoTa'],
                    'GiaBan' => $row['GiaBan'],
                    'SoLuong' => $row['SoLuongTong'],
                    'GioiTinh' => $row['GioiTinh'],
                    'DanhMuc' => $danhmuc,
                    'KhuyenMai' => $khuyenmai,
                    'Anh' => $anh
                );
            }
        }

        return $data;
    }
}
