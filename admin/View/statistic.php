<?php
require_once('../Controller/statistic_Controller.php');

// Khởi tạo Controller
$statisticController = new statistic_Controller();

// Lấy danh sách hóa đơn
// $invoices = $statisticController->getInvoices();

// Lấy danh sách sản phẩm bán chạy
$topProducts = $statisticController->getTopProducts();
// Giả sử $statisticController->monthly_revenue(date("Y-m-d")) trả về đối tượng mysqli_result
$monthlyRevenue = $statisticController->monthly_revenue(date("Y-m-d"));

// // Chuyển kết quả trả về thành một mảng kết hợp (associative array)
// $monthlyRevenueArray = $monthlyRevenue ? $monthlyRevenue->fetch_all(MYSQLI_ASSOC) : [];

// // Mã hóa mảng thành JSON
// echo json_encode($monthlyRevenueArray);


?>




<!-- <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/0af0d96f18.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body> -->

<?php
require_once "sidebar.php" ?>
<div id="statistic-content" class="content-container" style="max-width:80%">
    <div class="Mange_client" >
        <h3> Thống kê </h3>
        <!-- <div style="display: flex; align-items: baseline;">
            <p> Từ: </p>
            <input type="date" id="day-start">
            <p> Đến: </p>
            <input type="date" id="day-end">
            <button class="btn "style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button">
            <i class="fas fa-search"></i>
            </button>
        </div>   -->

        <!-- Bảng thống kê hóa đơn -->
        <!-- <div id="table-container"  class="table-responsive">
            <h3> Danh sách hóa đơn </h3>
            <table id="table-invoices" class="table table-custom table-striped table-hover">
            </table>
            <div class="pagination">
            <a onclick="prevPage()"><i class="fa fa-chevron-left"></i></a>
            <span id="pageNumbers"></span>
            <a onclick="nextPage()"><i class="fa fa-chevron-right"></i></a>
            </div>
        </div> -->

        <!-- Bảng Top 5 người dùng mua hàng nhiều nhất -->
        <div>
            <h4> Top 5 người dùng mua hàng nhiều nhất </h4>
            <div style="display: flex; align-items: baseline;">
            <p> Từ: </p>
            <input type="date" id="day-start-top-buy">
            <p> Đến: </p>
            <input type="date" id="day-end-top-buy">
            <button  onclick="findTopUser()" class="btn "style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button">
            <i class="fas fa-search"></i>
            </div> 
            <div class="table-responsive">
                <table id="table-top-5-user" class="table table-custom table-striped table-hover">
                </table>
            </div>
        </div>

        <!-- Dropdown chọn tháng -->
        <div>
            <h4> Tình hình kinh doanh </h4>
            <form action="">
            <select id="selected_date" name="month_year">
                <?php
                $startYear = 2024;
                $startMonth = 1;
                $endYear = date('Y'); // Năm hiện tại
                $endMonth = date('m'); // Tháng hiện tại
                $currentValue = sprintf("%02d-%04d", $endMonth, $endYear);

                for ($year = $startYear; $year <= $endYear; $year++) {
                    $monthStart = ($year == $startYear) ? $startMonth : 1;
                    $monthEnd = ($year == $endYear) ? $endMonth : 12;

                    for ($month = $monthStart; $month <= $monthEnd; $month++) {
                        $value = sprintf("%02d-%04d", $month, $year);
                        $selected = ($value == $currentValue) ? "selected" : "";
                        echo "<option value='$value' $selected>$month-$year</option>";
                    }
                }
                ?>
            </select>
            <div id="chart-result">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>
</body>
<script src="../../public/admin/js/statistic.js"></script>

</html>

