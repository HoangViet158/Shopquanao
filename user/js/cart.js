// Xử lý xóa sản phẩm
$(document).on('click', '.xoa-sp', function(e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    const maSP = row.data('masp');
    const maSize = row.data('masize');
    if(soLuong < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Số lượng không hợp lệ',
            text: 'Vui lòng nhập số lượng lớn hơn 0.',
        });
    }
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
    
    $.ajax({
        url: '../../user/API/index.php?type=deleteCartItem',
        method: 'POST',
        data: {
            maSP: maSP,
            maSize: maSize
        },
        success: function(response) {
            if (response.success) {
                row.remove();
                calculateTotal();
                if ($('#cart-body tr[data-masp]').length === 0) {
                    $('#dat-hang').prop('disabled', true);
                }
            } else {
                alert(response.error || 'Xóa sản phẩm thất bại');
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        }
    });
});

    $(document).on('click', '#dat-hang', function() {
        window.location.href = '../../user/View/order.php';
    })
// Xử lý cập nhật số lượng
$(document).on('change', 'input[type="number"]', function() {
    const row = $(this).closest('tr');
    const maSP = row.data('masp');
    const maSize = row.data('masize');
    const soLuong = $(this).val();
    console.log(soLuong);
    if(soLuong < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Số lượng không hợp lệ',
            text: 'Vui lòng nhập số lượng lớn hơn 0.',
        });
    }
    $.ajax({
        url: '../../user/API/index.php?type=updateCartItem',
        method: 'POST',
        data: {
            maSP: maSP,
            maSize: maSize,
            soLuong: soLuong
        },
        success: function(response) {
            if (!response.success) {
                alert(response.error || 'Cập nhật số lượng thất bại');
                // Reload lại trang nếu cập nhật thất bại
                location.reload();
            } else {
                // Tính lại tổng tiền
                calculateTotal();
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        }
    });
});

// Tính tổng tiền
function calculateTotal() {
    let total = 0;
    $('#cart-body tr[data-masp]').each(function() {
        const gia = parseInt($(this).find('td:nth-child(2)').text().replace(/\.|đ/g, ''));
        const soLuong = parseInt($(this).find('input').val());
        const tien = gia * soLuong;
        $(this).find('.tien').text(tien.toLocaleString('vi-VN') + 'đ');
        total += tien;
    });
    $('#tong-tien').text(total.toLocaleString('vi-VN') + 'đ');
}