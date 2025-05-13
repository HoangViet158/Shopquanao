<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="/Shopquanao/user/css/cart.css">
</head>
<body>
    <h2>Giỏ hàng</h2>
    <form>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Giảm giá</th>
                    <th>Số lượng</th>
                    <th>Size</th>
                    <th>Tổng tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php
                $tong = 0;
                foreach ($items as $item):
                    $gia = $item['GiaBan'];
                    $soLuong = $item['SoLuong'];
                    $soLuongTon = $item['SoLuongTon'];
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
                    <td>0đ</td>
                    <td>
                        <input type="number" value="<?= $soLuong ?>" min="1" max="<?= $soLuongTon ?>"
                               data-masp="<?= $item['MaSP'] ?>" data-masize="<?= $item['MaSize'] ?>">
                    </td>
                    <td><?= $item['TenSize'] ?></td>
                    <td class="tien"><?= number_format($tien, 0, ',', '.') ?>đ</td>
                    <td><a href="#" class="xoa-sp">[X]</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-footer">
            <a href="#" class="btn">Mua tiếp</a>
            <div class="total">Tổng tiền: <span id="tong-tien"><?= number_format($tong, 0, ',', '.') ?>đ</span></div>
            <button class="btn">Đặt hàng</button>
        </div>
    </form>

    <script src="/Shopquanao/user/js/cart.js"></script>
</body>
</html>