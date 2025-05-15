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

</head>

<header>
    <div class="welcome-banner">
        <h4>Chào mừng bạn đến với cửa hàng chúng tôi</h4>
    </div>
    <div class="title">
        <div class="logo">
            <img src="../../upload/products/logo-shop-quan-ao-nam-9.jpg" alt="logo">
        </div>
        <div class="nav-links">
            <a href="/">Trang chủ</a>
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
                <button class="btn btn-outline-secondary findByKeyword" type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            <div class="icons">
                <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                <?php if (isset($_SESSION['user'])): ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-info-dropdown">
                        <a href="user_info.php"><i class="fa-solid fa-user"></i></a>
                    </div>
            </div>
            <div class="user-info-form">
                <?php if($_SESSION['user']['permission'] != 3):?>
                    <a href="../../admin/View/statistic.php"><i class="fa-solid fa-gear"></i></a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fa-solid fa-user"></i></a>
        <?php endif; ?>
        </div>
    </div>
</header>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../public/user/js/products.js"></script> -->