<?php include __DIR__ . '/header.php'; ?>
<?php
if (!isset($_SESSION['user'])) {
      $path = $_SERVER['PHP_SELF'];
    $parts = explode('/', str_replace('\\', '/', $path));
    $index = array_search("user", $parts);        
    $projectFolder = $parts[$index - 1];
    $fullPath = "/user/View/product.php";
    $urlPath = "/" . $projectFolder . $fullPath;
    $succesPath = "/" . $projectFolder . "/user/View/index.php";
    header("Location: " . $urlPath);
    exit();
}

require_once(__DIR__ . '/../../user/Controller/CartController.php');
$cartController = new CartController();
$items = $cartController->getCart($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../../public/user/css/cart.css">
</head>
<body>
    <div class="mt-5 mb-4">
        <h2>Giỏ hàng</h2>
    </div>
    <div id="cart-container">
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <!-- <th>Giảm giá</th> -->
                    <th>Số lượng</th>
                    <th>Size</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php if (!empty($items)): ?>
                    <?php $tong = 0; ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $gia = $item['GiaBan'];
                        $soLuong = $item['SoLuong'];
                        $soLuongTong = $item['SoLuongTong'];
                        $tien = $gia * $soLuong;
                        $tong += $tien;
                        $anhList = explode(',', $item['HinhAnh']);
                        ?>
                        <tr data-masp="<?= $item['MaSP'] ?>" data-masize="<?= $item['MaSize'] ?>">
                            <td>
                                <img src="/Shopquanao/<?= $anhList[0] ?>" alt="Ảnh sản phẩm" width="80">
                                <div><?= $item['TenSP'] ?></div>
                            </td>
                            <td><?= number_format($gia, 0, ',', '.') ?>đ</td>
                            <!-- <td>0đ</td> -->
                            <td>
                                <input type="number" value="<?= $soLuong ?>" min="1" max="<?= $soLuongTong ?>"
                                       data-masp="<?= $item['MaSP'] ?>" data-masize="<?= $item['MaSize'] ?>">
                            </td>
                            <td><?= $item['TenSize'] ?></td>
                            <td class="tien"><?= number_format($tien, 0, ',', '.') ?>đ</td>
                            <td><button class="xoa-sp">X</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Giỏ hàng trống</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="cart-footer ">
            <a href="product.php" class="btn btn-danger">Mua tiếp</a>
            <div class="total">Tổng tiền: <span id="tong-tien"><?= isset($tong) ? number_format($tong, 0, ',', '.') : '0' ?>đ</span></div>
            <button id="dat-hang" class="dh" <?= empty($items) ? 'disabled' : '' ?>>Đặt hàng</button>
        </div>
    </div>
    
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="/Shopquanao/user/js/cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Shopquanao/public/user/js/products.js"></script>
</body>
<?php include __DIR__ . '/footer.php'; ?>
</html>