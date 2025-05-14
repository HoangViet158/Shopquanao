<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lọc sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/user/css/products.css" rel="stylesheet">
    
</head>
<?php include __DIR__ . '/../header.php'; ?>
<!-- code đc render từ file js trong thư mục public/user/js/products.js -->

<body>
    <div class="container mt-4">
        <div class="row pt-4">
            <!-- Phần bộ lọc -->
            <div class="col-md-3">
                <?php include __DIR__ . '/filters.php'; ?>
            </div>

            <!-- Phần danh sách sản phẩm -->
            <div class="col-md-9">
                <div id="product-list">

                </div>

                <!-- Phân trang -->
                <div id="pagination">

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/user/js/products.js"></script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>

</html>