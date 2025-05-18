<?php
require_once 'header.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Trang chủ</title>
  <link href="../css/home.css" rel="stylesheet">

</head>
<body>
    <div class="">
        <!-- <header class="main-header">
            <div class="logo">
                <a href="index.php">
                    <img src="../../upload/products/logo-shop-quan-ao-nam-9.jpg" alt="">
                </a>
            </div>
            <nav class="nav-menu">
            <a href="index.php">TRANG CHỦ</a>
            <a href="product.php">SẢN PHẨM</a>
            </nav>
            <div class="search-bar">
            <input type="text" placeholder="Nhập từ khóa tìm kiếm">
            <button><i class="fas fa-search"></i></button>
            </div>
            <div class="header-icons">
            <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
            <a href="login.php"><i class="fas fa-user"></i></a>
            </div>
        </header> -->

        <section class="container-fluid mt-4">
            <img class="top-banner img-fluid" src="../../upload/products/slide1.jpg" alt="">
            

            <!--Danh muc 
            <div class="product-catalog">
                <div class="catalog-header">
                    <h2 class="section-title">DANH MỤC SẢN PHẨM</h2>
                    <div class="catalog-buttons">
                        
                    </div>
                </div>
            </div>
                        -->


            <!--All products-->
            <div class="all-products">
                <h2 class="section-title">TẤT CẢ SẢN PHẨM</h2>
                <div class=" mt-4 container">
                        <div class="section-products">
                            <!-- sản phẩm ở đây -->
                        </div>
                    </div>

                    <div class="view-more-container">
                        <a href="product.php" class="view-more-button">Xem thêm</a>
                    </div>  
                </div>

            </div>
            

            <!--San pham ban chay -->
            <div class="best-selling-product">
                <h2 class="section-title">SẢN PHẨM BÁN CHẠY</h2>
                <div class=" mt-4 container">
                    <div class="product-grid"></div>
                </div>
                <div class="view-more-container">
                        <a href="product.php" class="view-more-button">Xem thêm</a>
                    </div>
                </div>

            </div>


        <footer class="footer-container">
                <div class="footer-left">
                    <h3>You & Me Shop</h3>
                    <div class="footer-section">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-center">
                    <p><i class="fas fa-map-marker-alt"></i> Đường An Dương Vương, Quận 5, TP. HCM</p>
                    <p><i class="fas fa-phone"></i> 0909096407</p>
                    <p><i class="fas fa-envelope"></i> dt@gmail.com</p>
                </div>
                <div class="footer-right">
                    <ul>
                        <li><i class="fas fa-file-contract"></i> <a href="#">Điều khoản sử dụng</a></li>
                        <li><i class="fas fa-shield-alt"></i> <a href="#">Chính sách bảo mật</a></li>
                        <li><i class="fas fa-exchange-alt"></i> <a href="#">Trả đổi hàng</a></li>
                        <li><i class="fas fa-truck"></i> <a href="#">Chính sách vận chuyển</a></li>
                        <li><i class="fas fa-headset"></i> <a href="#">Chăm sóc khách hàng</a></li>
                    </ul>

                </div>

        </footer> 
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../public/user/js/products.js"></script>
    <script src="../../public/user/js/home_product.js"></script>


</body>
</html>