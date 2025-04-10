<?php 
require_once ('../../config/connect.php');
class statistic_model {
    private $database = null;
    public function __construct(){
        $this->database = new Database();
    }

    public function invoices_statistic(){
        $sql = "SELECT hoadon.MaHD, 
                        hoadon.ThoiGian, 
                        hoadon.ThanhToan, 
                        COALESCE(khachhang.MaKH, nhanvien.MaNV) AS MaNguoiDung, 
                        CASE 
                            WHEN khachhang.MaKH IS NOT NULL THEN khachhang.TenKH 
                            WHEN nhanvien.MaNV IS NOT NULL THEN nhanvien.TenNV 
                            ELSE 'Không xác định' 
                        END AS TenNguoiDung
                FROM hoadon
                LEFT JOIN khachhang ON hoadon.MaTK = khachhang.MaTK
                LEFT JOIN nhanvien ON hoadon.MaTK = nhanvien.MaTK";
        return $this->database->execute($sql);
    }

    public function getTopProducts(){
        $sql = "SELECT 
                    sanpham.MaSP, 
                    sanpham.TenSP,
                    sanpham.GiaBan,
                    COUNT(cthoadon.MaSP) AS total_quantity, 
                    SUM(sanpham.GiaBan * cthoadon.MaSP) AS total_revenue
                FROM cthoadon
                JOIN sanpham ON sanpham.MaSP = cthoadon.MaSP
                GROUP BY sanpham.MaSP, sanpham.TenSP
                ORDER BY total_quantity DESC
                LIMIT 10";
        return $this->database->execute($sql);
    }

    public function monthly_revenue($date) {
        // Tách năm và tháng từ chuỗi $date (dạng "YYYY-MM")
        list($month, $year) = explode('-', $date);
        
        // Sử dụng prepared statement để tránh SQL injection
        $sql = "SELECT DATE(ThoiGian) AS order_date, SUM(ThanhToan) AS total
                FROM hoadon
                WHERE YEAR(ThoiGian) = ? AND MONTH(ThoiGian) = ?
                GROUP BY order_date
                ORDER BY order_date ASC";
    
        // Chuẩn bị câu lệnh SQL
        $stmt = $this->database->prepare($sql);
        
        // Liên kết tham số với câu lệnh chuẩn bị (prepared statement)
        $stmt->bind_param("ii", $year, $month); // "ii" nghĩa là kiểu dữ liệu là integer (i) cho năm và tháng
        
        // Thực thi câu lệnh SQL
        $stmt->execute();
        
        // Trả về kết quả
        return $stmt->get_result();
    }
    

}
?>