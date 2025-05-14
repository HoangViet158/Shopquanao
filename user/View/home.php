<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Trang chủ</title>
  <link href="/Shopquanao/public/user/css/home.css" rel="stylesheet">

</head>
<body>
  <header class="main-header">
    <div class="logo">
        <a href="index.php">
            <img src="../upload/products/logo-shop-quan-ao-nam-9.jpg" alt="Shop Logo">
        </a>
    </div>
    <nav class="nav-menu">
      <a href="index.php">Trang chủ</a>
      <a href="product.php">Sản phẩm</a>
    </nav>
    <div class="search-bar">
      <input type="text" placeholder="Nhập từ khóa tìm kiếm">
      <button><i class="fas fa-search"></i></button>
    </div>
    <div class="header-icons">
      <a href="../user/view/cart.php"><i class="fas fa-shopping-cart"></i></a>
      <?php if (isset($_SESSION['MaTK'])): ?>
    <a href="../user/view/user_info.php"><i class="fas fa-user"></i></a>
    <?php else: ?>
        <a href="../user/view/login.php"><i class="fas fa-user"></i></a>
    <?php endif; ?>
    </div>
  </header>

  <section class="home-container">

    <div class="home-img">
        <img class="top-banner" src="../upload/products/slide1.jpg" alt="">
    </div>    

    <!--Danh muc -->
    <div class="product-catalog">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="catalog-buttons">
            <a href="#"><button>Áo thun</button></a>
            <a href="#"><button>Áo khoác</button></a>
            <a href="#"><button>Áo dài tay</button></a>
            <a href="#"><button>Áo thể thao</button></a>
            <a href="#"><button>Áo jumper</button></a>
        </div>
    </div>

    <!--San pham ban chay-->
    <div class="best-selling-product">
        <div class="top-section">
            <h2 class="section-title">Sản phẩm bán chạy</h2>
            <button class="view-more">Xem thêm sản phẩm</button>
        </div>
        <div class="product-box">
            <div class="product-grid">
                <div class="product-card">
                    <img src="../upload/products/aothuncotrontaydai.jpg" alt="">
                    <h3>Áo thun cổ tròn</h3>
                    <p>250.000đ</p>
                </div>
                
                <div class="product-card">
                    <img src="../upload/products/aokhoackaki.jpg" alt="">
                    <h3>Áo khoác kaki</h3>
                    <p>350.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aopolonam-den.jpg" alt="">
                    <h3>Áo polo nam đen</h3>
                    <p>200.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aosomitrang.jpg" alt="">
                    <h3>Áo sơ mi trắng</h3>
                    <p>280.000đ</p>
                </div>

            </div>
        </div>
    </div>

    <!--Kham pha-->
    <div class="explore-product">
        <h2 class="section-title">Khám phá sản phẩm của chúng tôi</h2>

        <div class="product-box">
            <div class="product-grid">
                <div class="product-card">
                    <img src="../upload/products/aothuncotroncottonxam.jpg" alt="">
                    <h3>Áo thun cổ tròn cotton</h3>
                    <p>250.000đ</p>
                </div>
                
                <div class="product-card">
                    <img src="../upload/products/aopolonam.jpg" alt="">
                    <h3>Áo polo nam</h3>
                    <p>300.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aothunambasic.jpg" alt="">
                    <h3>Áo thun nam</h3>
                    <p>200.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aokhoacdu.jpg" alt="">
                    <h3>Áo khoác nam</h3>
                    <p>240.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aothuncotrontaydaiden.jpg" alt="">
                    <h3>Áo sơ tay dài</h3>
                    <p>200.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aothunrong.jpg" alt="">
                    <h3>Áo mới</h3>
                    <p>300.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aokhoacjean.jpg" alt="">
                    <h3>Áo jean</h3>
                    <p>350.000đ</p>
                </div>

                <div class="product-card">
                    <img src="../upload/products/aosomitayngan.jpg" alt="">
                    <h3>Áo sơ mi ngắn tay</h3>
                    <p>280.000đ</p>
                </div>
                
            </div>
            <div class="view-more-container">
                <button class="view-more">Xem tất cả sản phẩm</button>
            </div>
        </div>
    </div>
  </section>

  
  <footer class="footer-container">
        <div class="footer-left">
            <h3>You & Me Shop</h3>
            <div class="footer-section">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        </div>
        


        <div class="footer-right">
            <p>Đường An Dương Vương, Quận 5, TP. HCM</p>
            <p>0909096407</p>
            <p>dt@gmail.com</p>
        </div>
        <div class="footer-section">
            <ul>
                <li><a href="#">Điều khoản sử dụng</a></li>
                <li><a href="#">Chính sách bảo mật</a></li>
                <li><a href="#">Trả hàng đổi</a></li>
                <li><a href="#">Chính sách vận chuyển</a></li>
                <li><a href="#">Chăm sóc khách hàng</a></li>
            </ul>
        </div>

  </footer>

</body>
</html>
