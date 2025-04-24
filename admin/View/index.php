<html lang="en">

<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../admin/css/index.css" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>


<body>
    <div class="main-container">
        <div class="sidebar-container">
            <div class="admin-sidebar-header">
                
                    <img class="admin-avatar" src="/upload/products/logoadmin.jpg" alt="Admin Avatar" />
               
                <div class="admin-sidebar-info">
                    <span class="admin-sidebar-name">admin</span>
                    <span class="admin-sidebar-email">admin@gmail.com</span>
                </div>
            </div>
            
            <!-- Menu sidebar -->
            <div class="content_side">
                <div class="side_item" data-route="/products">
                    <div><i class="fa-solid fa-shop"></i></div>
                    <div><span>Quản Lí Sản Phẩm</span></div>
                </div>
                <div class="side_item" data-route="/bills">
                    <div><i class="fa-solid fa-truck-fast"></i></div>
                    <div><span>Quản Lí Đơn Hàng</span></div>
                </div>
                <div class="side_item" data-route="/goods-receipts">
                    <div><i class="fa-solid fa-pen"></i></div>
                    <div><span>Quản Lí Phiếu Nhập</span></div>
                </div>
                <div class="side_item">
                    <div><i class="fa-solid fa-user"></i></div>
                    <div><span>Quản lí User</span></div>
                </div>
                <div class="side_item" >
                    <div><i class="fa-solid fa-user-group"></i></div>
                    <div><p>Quản Lí Quyền</p></div>
                </div>
                <div class="side_item" >
                    <div><i class="fa-solid fa-money-bill-wave"></i></div>
                    <div><p>Khuyến Mãi</p></div>
                </div>
                <div class="side_item" >
                    <div><i class="fa-solid fa-chart-simple"></i></div>
                    <div><p>Thống Kê</p></div>
                </div>
                <div class="side_item" >
                    <div><i class="fa-solid fa-home"></i></div>
                    <div><span>Trang Chủ</span></div>
                </div>
                <div class="side_item" data-route="/">
                    <div><i class="fa-solid fa-home"></i></div>
                    <div><span>Trang Chủ</span></div>
                </div>
            </div>
        </div>

        <!-- Phần nội dung chính -->
        <div class="content-container">
            <!-- Thanh công cụ -->
            
            <div class="Mange_client" >
                <div class="admin_home">
                    <h4>Chào Mừng Đến Với Trang Quản Trị</h4>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../admin/js/router.js"></script>
<script src="../admin/js/Product.js"></script>
<script src="../admin/js/goodsReceipt.js"></script>
<script src="../admin/js/bill.js"></script>

    <!-- Script xử lý chung -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Xử lý click menu
        document.querySelectorAll('.side_item').forEach(item => {
            item.addEventListener('click', function() {
                const route = this.getAttribute('data-route');
                if (route && window.router) {
                    router.navigate(route);
                }
            });
        });

        // Đăng ký global handlers (nếu cần)
        if (window.handleProduct) router.registerHandler('handleProduct', handleProduct);
        if (window.handleBill) router.registerHandler('handleBill', handleBill);
        if (window.handleGoodsReceipt) router.registerHandler('handleGoodsReceipt', handleGoodsReceipt);
        if (window.router) {
        router.init();
    }
    });
    </script>
</body>
</html>