document.addEventListener('DOMContentLoaded', function () {
    const totalSpan = document.getElementById('tong-tien');

    // Hàm cập nhật tổng tiền toàn bộ giỏ hàng
    function capNhatTong() {
        let tong = 0;
        document.querySelectorAll('.tien').forEach(cell => {
            tong += parseInt(cell.textContent.replace(/\D/g, '') || 0);
        });
        totalSpan.textContent = tong.toLocaleString('vi-VN') + 'đ';
    }

    // Sự kiện cập nhật số lượng
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', function () {
            const row = input.closest('tr');
            const donGia = parseInt(row.querySelector('td:nth-child(2)').textContent.replace(/\D/g, '')) || 0;
            const soLuong = parseInt(input.value) || 1;
            const thanhTien = donGia * soLuong;

            // Cập nhật lại cột thành tiền
            row.querySelector('.tien').textContent = thanhTien.toLocaleString('vi-VN') + 'đ';

            // Cập nhật tổng tiền toàn bộ
            capNhatTong();

            // Gửi Ajax để lưu số lượng vào DB
            const maSP = input.dataset.masp;
            const maSize = input.dataset.masize;

            fetch('/Shopquanao/user/Ajax/update_quantity.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `MaSP=${maSP}&MaSize=${maSize}&SoLuong=${soLuong}`
            })
            .then(res => res.text())
            .then(data => {
                console.log(data); // Kiểm tra phản hồi từ server
            });
        });
    });

    // Hàm xoá (giữ nguyên như đoạn trước)
    document.querySelectorAll('.xoa-sp').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const row = this.closest('tr');
            const maSP = row.dataset.masp;
            const maSize = row.dataset.masize;

            if (!confirm("Bạn có chắc chắn muốn xoá sản phẩm này khỏi giỏ hàng không?")) return;

            fetch('/Shopquanao/user/Ajax/delete_cart_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `MaSP=${maSP}&MaSize=${maSize}`
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === 'success') {
                    row.remove();
                    capNhatTong();
                    alert('Sản phẩm đã được xoá khỏi giỏ hàng.');
                } else {
                    alert('Lỗi khi xoá: ' + data);
                }
            });
        });
    });
});