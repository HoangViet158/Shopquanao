<?php 
require_once('../Controller/auth_helper.php');
requireLogin();
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../public/admin/css/index.css" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>


<body>
    <div class="main-container">
        <div class="sidebar-container">
            <div class="admin-sidebar-header">

                <img class="admin-avatar" src="../../upload/products/logoadmin.jpg" alt="Admin Avatar" />

                <div class="admin-sidebar-info">
                    <span class="admin-sidebar-name">admin</span>
                    <span class="admin-sidebar-email">admin@gmail.com</span>
                </div>
            </div>

            <!-- Menu sidebar -->
            <div class="content_side">
                <?php if (canViewProduct()): ?>
                    <a href="product.php" style="text-decoration: none;">
                        <div class="side_item">
                            <div><i class="fa-solid fa-shop"></i></div>
                            <div><span>Quản Lí Sản Phẩm</span></div>
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (canViewBill()): ?>          
                <a href="bill.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-truck-fast"></i></div>
                        <div><span>Quản Lí Đơn Hàng</span></div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (canViewGoodReceipt()): ?>
                <a href="goodReceipt.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-pen"></i></div>
                        <div><span>Quản Lí Phiếu Nhập</span></div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (canViewUser()): ?>
                <a href="user.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-user"></i></div>
                        <div><span>Quản lí Khách Hàng</span></div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (canViewPermission()): ?>
                <a href="permission.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-user-group"></i></div>
                        <div>
                            <p>Quản Lí Quyền</p>
                        </div>
                    </div>
                </a>
                <?php endif; ?>

                <?php if (canViewPromotion()): ?>
                <a href="promotion.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-money-bill-wave"></i></div>
                        <div>
                            <p>Khuyến Mãi</p>
                        </div>
                    </div>
                </a>
                <?php endif; ?>

                <a href="statistic.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-chart-simple"></i></div>
                        <div>
                            <p>Thống Kê</p>
                        </div>
                    </div>
                </a>
                <a href="../../user/View/index.php" style="text-decoration: none;">
                    <div class="side_item">
                        <div><i class="fa-solid fa-home"></i></div>
                        <div><span>Trang Chủ</span></div>
                    </div>
                </a>
            </div>
        </div>