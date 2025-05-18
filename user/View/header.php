<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../../public/user/css/user.css" />
    <title>Shop Quần áo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<?php session_start(); ?>
<style>
   /* Enhanced dropdown menu styles */
   
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
  border-radius: 0 6px 6px 6px;
  box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

.dropdown-submenu > a:after {
  display: block;
  content: " ";
  float: right;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  border-left-color: #ccc;
  margin-top: 5px;
  margin-right: -10px;
}

.dropdown-submenu:hover > a:after {
  border-left-color: #fff;
}

/* Responsive header styles */
@media (max-width: 991px) {
  .title {
    flex-direction: column;
    align-items: center;
  }
  .logo{
    display: none;
  }
  .nav-links {
    margin: 10px 0;
    width: 100%;
    display: flex;
    justify-content: center;
  }
  
  .search-cart {
    width: 100%;
    justify-content: center;
    margin-top: 10px;
  }
  
  .dropdown-submenu > .dropdown-menu {
    left: 0;
    top: 100%;
    margin-top: 0;
    margin-left: 0;
    border-radius: 0 0 6px 6px;
  }
  
  .dropdown-submenu > a:after {
    transform: rotate(90deg);
  }
}

/* Enhanced header styling */
.title {
  padding: 15px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo img {
  max-height: 60px;
  object-fit: contain;
}

.nav-links {
  display: flex;
  gap: 20px;
}

.nav-links a {
  color: #333;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}

.nav-links a:hover {
  color: #0d6efd;
}

.search-cart {
  display: flex;
  align-items: center;
  gap: 15px;
}

.search-cart .input-group {
  width: 250px;
}

.icons {
  display: flex;
  gap: 15px;
}

.icons a {
  color: #333;
  font-size: 1.2rem;
  transition: color 0.3s;
}
/* Thêm vào phần CSS hiện có */
#type-filters-container {
    margin-top: 10px;
    border-left: 3px solid #0d6efd;
    padding-left: 15px;
}

.type-filter {
    margin-left: 20px;
}

#type-filters small {
    font-weight: 500;
    color: #495057 !important;
}
.icons a:hover {
  color: #0d6efd;
}

/* Improved dropdown styling */
.dropdown-menu {
  border: none;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  padding: 10px 0;
}

.dropdown-item {
  padding: 8px 20px;
  transition: background-color 0.2s;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

.dropdown-toggle::after {
  margin-left: 8px;
}

/* Filter card styling */
.card.mb-4 {
  border: none;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #eee;
}

.form-check {
  margin-bottom: 8px;
}

.form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

</style>
<header>
    <div class="welcome-banner">
        <h4>Chào mừng bạn đến với cửa hàng chúng tôi</h4>
    </div>
    <div class="title">
        <div class="logo">
            <img src="../../upload/products/logo-shop-quan-ao-nam-9.jpg" alt="logo">
        </div>
        <div class="nav-links">
            <a href="home.php">Trang chủ</a>
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Danh mục sản phẩm
                </a>
                <ul class="dropdown-menu" aria-labelledby="categoriesDropdown" id="categoriesMenu">
                    <li><a class="dropdown-item" href="#" data-category="all">Tất cả sản phẩm</a></li>
                </ul>
            </div>
        </div>
        <div class="search-cart">
            <div class="input-group">
                <input type="text" class="nameTxt form-control" name="search" placeholder="Nhập từ khóa tìm kiếm">
                <button class="btn  findByKeyword" type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            <div class="icons">
                <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-info-dropdown">
                        <a href="user_info.php"><i class="fa-solid fa-user"></i></a>
                    </div>
            </div>
            <div class="user-info-form">
                <?php if ($_SESSION['user']['permission'] != 3): ?>
                    <a href="../../admin/View/statistic.php"><i class="fa-solid fa-gear"></i></a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fa-solid fa-user"></i></a>
        <?php endif; ?>
        </div>
    </div>
    <script src="../../public/user/js/products.js"></script>

</header>

