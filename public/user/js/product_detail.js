function loadCategories() {
        $.ajax({
            url: '../../admin/API/index.php?type=getAllCategories',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                const menu = $('#categoriesMenu');
                // Xóa item mặc định 
                if (data.length > 0) {
                    menu.empty();
                    menu.append('<li><a class="dropdown-item" href="#" data-category="all">Tất cả sản phẩm</a></li>');
                }
                
                // Thêm các danh mục vào dropdown
                data.forEach(category => {
                    menu.append(`
                        <li><a class="dropdown-item" href="#" data-category="${category.MaDM}">${category.TenDM}</a></li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh mục:", error);
            }
        });
    }
$(document).ready(function() {
    // Tải danh mục sản phẩm
        loadCategories();
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');
        
        if (!productId) {
            $('#product-detail-container').html('<div class="alert alert-danger">Không tìm thấy sản phẩm</div>');
            return;
        }
        
        // Gọi API lấy chi tiết sản phẩm
        $.ajax({
            url: `../../user/API/index.php?type=getProductDetail&id=${productId}`,
            type: "GET",
            dataType: "json",
            success: function(product) {
                if (!product) {
                    $('#product-detail-container').html('<div class="alert alert-danger">Sản phẩm không tồn tại</div>');
                    return;
                }
                
                renderProductDetail(product);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải chi tiết sản phẩm:", error);
                $('#product-detail-container').html('<div class="alert alert-danger">Lỗi khi tải thông tin sản phẩm</div>');
            }
        });
    });
    
        function renderProductDetail(product) {
            // Tính giá gốc nếu có khuyến mãi
            let originalPrice = '';
            let discountBadge = '';
            
            const discountValue = parseFloat(product.giaTriKM);
        
            if (product.MaKM && !isNaN(discountValue) && discountValue > 0) {
                const original = Math.round(product.GiaBan / (1 - discountValue/100));
                originalPrice = `<span class="original-price ms-2">${formatPrice(original)} đ</span>`;
                discountBadge = `<span class="discount-badge ms-2">-${discountValue}%</span>`;
            }
            
            // Tạo HTML cho gallery ảnh
            let galleryHtml = '';
            if (product.Anh && product.Anh.length > 0) {
                const mainImage = product.Anh[0].Url;
                
                galleryHtml = `
                <div class="product-gallery ">
                    <img src="../../${mainImage}" class="main-image mb-3" id="main-image" alt="${product.TenSP}">
                    <div class="thumbnail-container border p-2 rounded shadow-sm">
                `;
                
                product.Anh.forEach((image, index) => {
                    galleryHtml += `
                    <img src="../../${image.Url}" class="thumbnail ${index === 0 ? 'active' : ''}" 
                        onclick="changeMainImage('${image.Url}')" alt="Ảnh ${index + 1}">
                    `;
                });
                
                galleryHtml += '</div></div>';
            }
            
            // Tạo HTML chi tiết sản phẩm
            const html = `
            <div class="row">
                <div class="col-md-6">
                    ${galleryHtml}
                </div>
                <div class="col-md-6">
                    <h2 class="my-3">${product.TenSP}</h2>
                    <div class="d-flex align-items-center my-3 ">
                        <h4 class="text-danger mb-0">${formatPrice(product.GiaBan)} đ</h4>
                        ${originalPrice}
                        ${discountBadge}
                    </div>
                    
                    
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông tin chi tiết</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th width="30%">Mã sản phẩm</th>
                                    <td>${product.MaSP || 'Không xác định'}</td>
                                </tr>
                                <tr>
                                    <th>Mô tả </th>
                                    <td>${product.MoTa || 'Không có'}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Danh mục</th>
                                    <td>${product.TenDM || 'Không xác định'}</td>
                                </tr>
                                <tr>
                                    <th>Ngày đăng</th>
                                    <td>${formatDate(product.NgayTao)}</td>
                                </tr>
                                <tr>
                                    <th>Số lượng tồn</th>
                                    <td>${product.SoLuongTong || 0}</td>
                                </tr>
                                <tr>
                                    <th>Giới tính</th>
                                    <td>${getGenderText(product.GioiTinh)}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    ${(product.SoLuongTong === 0 || product.GiaBan === 0)
                    ? `<div class="alert alert-warning">Sản phẩm này hiện không có sẵn.</div>`
                    : `
                    <div class="d-flex align-items-center my-3">
                        <div class="me-3">
                            <label>Số lượng:</label>
                            <input type="number" min="1" max="${product.SoLuongTong}" value="1" class="form-control" style="width: 80px;">
                        </div>
                        <div class="me-3">
                            <label>Size:</label>
                            <select class="form-select" id="size-select">
                                <option value="">Chọn kích cỡ</option>
                                ${product.Sizes && product.Sizes.length > 0 
                                    ? product.Sizes.map(size => `<option value="${size.MaSize}">${size.TenSize}</option>`).join('') 
                                    : ''}
                            </select>
                        </div>
                    </div>
                    <div class="d-flex my-3">
                            <button class="btn btn-danger me-2" id="add-to-cart-btn">Thêm vào giỏ hàng</button>
                    </div>
                    `}
                </div>
            </div>
            `;
            
            $('#product-detail-container').html(html);
            $('#add-to-cart-btn').on('click', () => addCart(product));

        }

    async function addCart(product) {
        const maSP = product.MaSP;
        const maSize = document.getElementById('size-select').value;
        const soLuong = parseInt(document.querySelector('input[type="number"]').value);

        if (!maSize) {
        alert('Vui lòng chọn kích cỡ!');
        return;
        }

        if (soLuong <= 0) {
            alert('Số lượng không hợp lệ!');
            return;
        }
        data = {
            'maSP' : maSP,
            'maSize' : maSize,
            'soluong' : soLuong
        }
        console.log(data)
        const res = await fetch('../../user/API/index.php?type=addCart', {
            method : 'POST',
            headers : {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify(data),
            credentials: 'include' // đảm bảo session vẫn hoạt động
        })
        if (!res.ok) throw new Error('lỗi')
        const result     = await res.text();
        console.log(result)
        // if (result.success){
        //     alert('Thêm giỏ hàng thành công!');
        // } else {
        //     alert('Thêm giỏ hàng thất bại!');
        // }
    }
    
    function changeMainImage(url) {
        $('#main-image').attr('src', '../../' + url);
        $('.thumbnail').removeClass('active');
        $(`.thumbnail[src="../../${url}"]`).addClass('active');
    }
    
    function formatPrice(price) {
        return parseInt(price).toLocaleString('vi-VN');
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'Không xác định';
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }
    
    function getGenderText(genderCode) {
        switch(genderCode) {
            case 0: return 'Nữ';
            case 1: return 'Nam';
            case 2: return 'Unisex';
            default: return 'Không xác định';
        }
    }