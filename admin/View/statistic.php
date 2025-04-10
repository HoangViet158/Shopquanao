<?php
require_once('../Controller/statistic_Controller.php');

// Khởi tạo Controller
$statisticController = new statistic_Controller();

// Lấy danh sách hóa đơn
$invoices = $statisticController->getInvoices();

// Lấy danh sách sản phẩm bán chạy
$topProducts = $statisticController->getTopProducts();
// Giả sử $statisticController->monthly_revenue(date("Y-m-d")) trả về đối tượng mysqli_result
$monthlyRevenue = $statisticController->monthly_revenue(date("Y-m-d"));

// Chuyển kết quả trả về thành một mảng kết hợp (associative array)
$monthlyRevenueArray = $monthlyRevenue ? $monthlyRevenue->fetch_all(MYSQLI_ASSOC) : [];

// Mã hóa mảng thành JSON
echo json_encode($monthlyRevenueArray);

?>
<script>
    // Truyền dữ liệu từ PHP sang JavaScript qua JSON
    let example = <?php 
        // Đảm bảo echo JSON chính xác từ PHP
        $monthlyRevenue = $statisticController->monthly_revenue("03-2025");
        echo json_encode($monthlyRevenue ? $monthlyRevenue->fetch_all(MYSQLI_ASSOC) : []); 
    ?>;
    console.log(example);
</script>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/0af0d96f18.js" crossorigin="anonymous"></script>
</head>
<body>

<div id="statistic-content">
    <h3> Thống kê </h3>
    <div style="display: flex; align-items: baseline;">
        <p> Từ: </p>
        <input type="date" id="day-start">
        <p> Đến: </p>
        <input type="date" id="day-end">
        <button id="search"> <i class="fa-solid fa-magnifying-glass"></i> Tìm </button>
    </div>  

    <!-- Bảng thống kê hóa đơn -->
    <div id="table-container">
        <h3> Danh sách hóa đơn </h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Mã HD</th>
                    <th>Mã khách hàng</th>
                    <th>Tên khách hàng</th>
                    <th>Thành tiền</th>
                    <th>Thời gian</th>
                    <th>Chi tiết hóa đơn</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $invoices->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['MaHD']; ?></td>
                        <td><?php echo $row['MaNguoiDung']; ?></td>
                        <td><?php echo $row['TenNguoiDung']; ?></td>
                        <td><?php echo number_format($row['ThanhToan'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo $row['ThoiGian']; ?></td>
                        <td><button id = "detail-invoice"> Xem chi tiết </td>                        
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Bảng sản phẩm bán chạy -->
    <div>
        <h3> Sản phẩm bán chạy </h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Tên SP</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $topProducts->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['MaSP']; ?></td>
                        <td><?php echo $row['TenSP']; ?></td>
                        <td><?php echo number_format($row['GiaBan'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo $row['total_quantity']; ?></td>
                        <td><?php echo number_format($row['total_revenue'], 0, ',', '.'); ?> VNĐ</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Dropdown chọn tháng -->
    <div>
        <h3> Tình hình kinh doanh </h3>
        <form action="">
        <select name="month_year">
            <?php
            $startYear = 2024;
            $startMonth = 1;
            $endYear = date('Y'); // Năm hiện tại
            $endMonth = date('m'); // Tháng hiện tại
            $currentValue = sprintf("%04d-%02d", $endYear, $endMonth);

            for ($year = $startYear; $year <= $endYear; $year++) {
                $monthStart = ($year == $startYear) ? $startMonth : 1;
                $monthEnd = ($year == $endYear) ? $endMonth : 12;

                for ($month = $monthStart; $month <= $monthEnd; $month++) {
                    $value = sprintf("%04d-%02d", $year, $month);
                    $selected = ($value == $currentValue) ? "selected" : "";
                    echo "<option value='$value' $selected>$month-$year</option>";
                }
            }
            ?>
        </select>
    </div>
</div>

</body>
</html>
