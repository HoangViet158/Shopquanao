<?php 
// require_once(__DIR__ . "/../../config/connect.php");   // lấy đường đẫn tuyệt đối chứ tương đối làm 1 hồi lỗi mẹ tùm lum
require_once ('../../config/connect.php');
class statistic_model {
    private $database = null;
    public function __construct(){
        $this->database = new Database();
    }

    public function invoices_statistic($limit,$offset,$daystart,$dayend,$id){
        $sql = "SELECT hoadon.MaHD, 
                        hoadon.ThoiGian, 
                        hoadon.ThanhToan, 
                        hoadon.MaTK,
                        taikhoan.TenTK,
                        hoadon.TrangThai
                FROM hoadon 
                JOIN taikhoan ON hoadon.MaTK = taikhoan.MaTK
                WHERE hoadon.ThoiGian BETWEEN ? AND ?
                AND hoadon.MaTK = ?
                ORDER BY hoadon.ThoiGian DESC
                LIMIT $offset, $limit";  // <-- Chèn thẳng

        $stmt = $this->database->connection()->prepare($sql);
        $dateStart = $daystart->format('Y-m-d');
        $dateEnd = $dayend->format('Y-m-d');
        $stmt->bind_param("ssi",  $dateStart, $dateEnd , $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function loadTop5User($start, $end) {
        $sql = "SELECT 
                    taikhoan.MaTK,
                    taikhoan.TenTK,
                    COUNT(hoadon.MaTK) AS Amount,
                    SUM(hoadon.ThanhToan) AS Total
                FROM taikhoan
                JOIN hoadon ON hoadon.MaTK = taikhoan.MaTK
                WHERE hoadon.ThoiGian BETWEEN ? AND ?
                AND hoadon.TrangThai NOT IN (0, 3)
                GROUP BY taikhoan.MaTK
                ORDER BY Total DESC
                LIMIT 5";
        
        $stmt = $this->database->prepare($sql);
        $dateStart = $start->format('Y-m-d');
        $dateEnd = $end->format('Y-m-d');
    
        $stmt->bind_param('ss', $dateStart, $dateEnd);
        $stmt->execute();
    
        return $stmt->get_result(); // phải trả mysqli_result
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
        $parts = explode('-', $date);
        if (count($parts) === 2) {
            // Nếu định dạng "MM-YYYY"
            $month = (int) $parts[0];
            $year  = (int) $parts[1];
        } else {
            // Nếu không đúng format, fallback về tháng/năm hiện tại
            $month = (int) date('m');
            $year  = (int) date('Y');
        }
        
        // Sử dụng prepared statement để tránh SQL injection
        $sql = "SELECT DATE(ThoiGian) AS order_date, SUM(ThanhToan) AS total
                FROM hoadon
                WHERE YEAR(ThoiGian) = ? AND MONTH(ThoiGian) = ?
                AND hoadon.TrangThai NOT IN (0, 3)
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

    public function total_invoices_statistic($daystart,$dayend,$id){
        $sql = "SELECT COUNT(hoadon.MaHD) AS total
                FROM hoadon 
                WHERE hoadon.ThoiGian BETWEEN ? AND ?
                AND hoadon.MaTK = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $dateStart = $daystart->format('Y-m-d');
        $dateEnd = $dayend->format('Y-m-d');
        $stmt->bind_param("ssi",  $dateStart, $dateEnd , $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    

}
?>