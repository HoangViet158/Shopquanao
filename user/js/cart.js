document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="number"]');
    const rows = document.querySelectorAll('#cart-body tr');
    const totalSpan = document.getElementById('tong-tien');

    // Hàm tính tổng
    function capNhatTong() {
        let tong = 0;
        document.querySelectorAll('.tien').forEach(cell => {
            tong += parseInt(cell.textContent.replace(/\D/g, ''));
        });
        totalSpan.textContent = tong.toLocaleString('vi-VN') + 'đ';
    }

    // Cập nhật số lượng
    inputs.forEach(input => {
        input.addEventListener('change', function () {
            const row = input.closest('tr');
            const donGia = parseInt(row.querySelector('td:nth-child(2)').textContent.replace(/\D/g, ''));
            const soLuong = parseInt(input.value);
            const thanhTien = donGia * soLuong;

            row.querySelector('.tien').textContent = thanhTien.toLocaleString('vi-VN') + 'đ';
            capNhatTong();

            const maSP = input.dataset.masp;
            const maSize = input.dataset.masize;

            fetch('/Shopquanao/user/Ajax/update_quantity.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `MaSP=${maSP}&MaSize=${maSize}&SoLuong=${soLuong}`
            })
            .then(res => res.text())
            .then(data => console.log(data));
        });
    });

    // Xoá sản phẩm
    document.querySelectorAll('.xoa-sp').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const row = this.closest('tr');
            const maSP = row.dataset.masp;
            const maSize = row.dataset.masize;

            fetch('/Shopquanao/user/Ajax/delete_cart_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `MaSP=${maSP}&MaSize=${maSize}`
            })
            .then(res => res.text())
            .then(data => {
                console.log(data);
                row.remove();
                capNhatTong();
            });
        });
    });
});