
$(document).ready(function() {
    loadCategories();
    const params = new URLSearchParams(window.location.search);
    if (params.toString() === '') {
        loadProductData();
    } else {
        filterProductData();
    }
    // Xử lý khi click vào danh mục
   $(document).on('click', '.dropdown-item[data-category]', function(e) {
    e.preventDefault();
    const categoryId = $(this).data('category');
    const currentPath = window.location.pathname;

    // Lấy đường dẫn đến index.php 
    const basePath = currentPath.substring(0, currentPath.lastIndexOf('/')) + '/product.php';
    
    let targetUrl = basePath;
    $('input[name="category"]').prop('checked', false);
    // Nếu không phải 'all', thêm categories vào URL
    if (categoryId !== 'all') {
        const params = new URLSearchParams();
        params.set('categories', categoryId);
        targetUrl += '?' + params.toString();
         
    }
    if (window.location.pathname.includes('product.php')) {
        window.history.pushState({}, '', targetUrl);
        filterProductData(); // Gọi AJAX để lọc
    } else {
        window.location.href = targetUrl; 
    }
});

    // Xử lý khi thay đổi bộ lọc
    $('.filter-option').change(function() {
        updateURLFromFilters();
        filterProductData();
    });
    // Xử lý khi click vào nút tìm kiếm
    $('.findByKeyword').on('click', () => {
    const params = new URLSearchParams(window.location.search);
    const keyword = $('.nameTxt').val().trim();
    
    if (keyword) {
        params.set('keyword', keyword);
        window.location.href = `/Shopquanao/user/View/product.php?${params.toString()}`;
    } else {
        params.delete('keyword');
    }
    
    window.history.pushState({}, '', `${location.pathname}?${params.toString()}`);
    filterProductData();
});
    // Xử lý khi click phân trang
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        updateURLParameter('page', page);
        const params = new URLSearchParams(window.location.search);
        if (params.toString() === '' || params.toString() === 'page='+page) {
            loadProductData(page);
        } else {
            filterProductData(page);
    }
    });
});
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
function updateURLFromFilters() {  //hàm cập nhật url
    const params = new URLSearchParams(window.location.search);

    const categories = [];
    $('input[name="category"]:checked').each(function() {
        categories.push($(this).val());
    });
    if (categories.length) params.set('categories', categories.join(','));
    else {
        params.delete('categories'); 
    }
    // Giá (1 lựa chọn)
    const price = $('input[name="price"]:checked').val();
    if (price) { // Chỉ thêm nếu có giá trị (không phải "Tất cả giá")
        params.set('price', price);
    } else {
        params.delete('price'); // Xóa nếu là "Tất cả giá"
    }

 
    const genders = [];
    $('input[name="gender"]:checked').each(function() {
        genders.push($(this).val());
    });
    if (genders.length) params.set('genders', genders.join(','));
    else {
        params.delete('genders');
    }
    const sizes = [];
    $('input[name="size"]:checked').each(function() {
        sizes.push($(this).val());
    });
    if (sizes.length) params.set('sizes', sizes.join(','));
    else {
        params.delete('sizes');
    }
    // Cập nhật URL mà không reload trang
    window.history.pushState({}, '', `${location.pathname}?${params.toString()}`);
}

function updateURLParameter(key, value) {
    const params = new URLSearchParams(window.location.search);
    params.set(key, value);
    window.history.pushState({}, '', `${location.pathname}?${params.toString()}`);
}
function updateActiveFilters() {   // hàm cập nhật trạng thái của các filter, kiểu thành checked hoặc unchecked
    const params = new URLSearchParams(window.location.search);
    
    // Cập nhật trạng thái checked cho các filter
    // Loại sản phẩm
    const categories = params.get('categories') ? params.get('categories').split(',') : [];
    $('input[name="category"]').prop('checked', false);
    
    categories.forEach(cat => {
        $(`input[name="category"][value="${cat}"]`).prop('checked', true);
    });
    
    // Giá
     const price = params.get('price');
    $('input[name="price"]').prop('checked', false);
    if (price) {
        $(`input[name="price"][value="${price}"]`).prop('checked', true);
    } else {
        $('#price-all').prop('checked', true);
    }
    
    // Giới tính
    const genders = params.get('genders') ? params.get('genders').split(',') : [];
    $('input[name="gender"]').prop('checked', false);
    genders.forEach(gender => {
        $(`input[name="gender"][value="${gender}"]`).prop('checked', true);
    });
    
    // Size
    const sizes = params.get('sizes') ? params.get('sizes').split(',') : [];
    $('input[name="size"]').prop('checked', false);
    sizes.forEach(size => {
        $(`input[name="size"][value="${size}"]`).prop('checked', true);
    });
}
function filterProductData(page = 1) {
    const params = new URLSearchParams(window.location.search);
    if (params.toString() === '') {
        loadProductData(page);
        return;
    }
    let apiUrl = `../../user/API/index.php?type=filter&page=${page}`;
    
    // Thêm các tham số filter 
    if (params.get('categories')) apiUrl += `&categories=${params.get('categories')}`;
    if (params.get('price')) apiUrl += `&price=${params.get('price')}`;
    if (params.get('genders')) apiUrl += `&genders=${params.get('genders')}`;
    if (params.get('sizes')) apiUrl += `&sizes=${params.get('sizes')}`;
    if( params.get('keyword')) apiUrl += `&keyword=${params.get('keyword')}`;
    $.ajax({
        url: apiUrl,  
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#product-list').html(renderProducts(data.products));
            $('#pagination').html(renderPagination(data.pagination));
            updateActiveFilters();
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi tải dữ liệu:", error);
        }
    });
}


function loadProductData(page = 1) {
    $.ajax({  
        url: `../../admin/API/index.php?type=getAllProducts&page=${page}`,
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#product-list').html(renderProducts(data.products));
            $('#pagination').html(renderPagination(data.pagination));
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi tải dữ liệu:", error);
        }
    });
}

function renderProducts(products) {
    
    if (products.length === 0) {
        return '<div class="alert alert-info">Không có sản phẩm nào phù hợp</div>';
    }
    
    let html = '<div class="row">';
    
    products.forEach(product => {
        const imageSrc = Array.isArray(product.Anh) ? product.Anh[0] : product.Anh;

        html += `
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <a href="product_detail.php?id=${product.MaSP}">
                    <img src="../..${imageSrc}" class="card-img-top" alt="${product.TenSP}">
                </a>
                <div class="card-body">
                    <h5 class="card-title">${product.TenSP}</h5>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-danger fw-bold">${formatPrice(product.GiaBan)} đ</span>
                        <button class="btn btn-sm btn-danger">Thêm vào giỏ</button>
                    </div>
                </div>
            </div>
        </div>
        `;
    });
    
    html += '</div>';
    return html;
}

function renderPagination(pagination) {
     if (pagination.total_pages <= 1) {
        $('#pagination').hide();
        return '';
    }
    
    $('#pagination').show();
    let html = `
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
    `;
    
    if (pagination.current_page > 1) {
        html += `
        <li class="page-item">
            <a class="page-link" href="?page=${pagination.current_page - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        `;
    }
    
    for (let i = 1; i <= pagination.total_pages; i++) {
        html += `
        <li class="page-item ${i == pagination.current_page ? 'active' : ''}">
            <a class="page-link" href="?page=${i}">${i}</a>
        </li>
        `;
    }
    
    if (pagination.current_page < pagination.total_pages) {
        html += `
        <li class="page-item">
            <a class="page-link" href="?page=${pagination.current_page + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        `;
    }
    
    html += `
        </ul>
    </nav>
    `;
    
    return html;
}

function formatPrice(price) {
    return parseInt(price).toLocaleString('vi-VN');
}