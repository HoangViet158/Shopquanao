<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/user/css/product_detail.css" rel="stylesheet">
    
</head>
<?php include __DIR__ . '/../header.php'; ?>

<body>
    <div class="container my-5 border rounded shadow-sm">
        <div id="product-detail-container">
            <!-- Nội dung sẽ được điền bằng JavaScript -->
            <div class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/user/js/product_detail.js"></script>
    <script src="../../../public/user/js/products.js"></script>
</body>
<?php include __DIR__ . '/../footer.php'; ?>

</html>