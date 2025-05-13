$(document).ready(function() {
    loadProductData();
    
    // Xử lý khi thay đổi bộ lọc
    $('.filter-option').change(function() {
        updateURLFromFilters();
        filterProductData();
    });
    
    // Xử lý khi click phân trang
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        updateURLParameter('page', page);
        loadProductData(page);
    });
});

function updateURLFromFilters() {
    const params = new URLSearchParams();

    const categories = [];
    $('input[name="category"]:checked').each(function() {
        categories.push($(this).val());
    });
    if (categories.length) params.set('categories', categories.join(','));
    
    // Giá (1 lựa chọn)
    const price = $('input[name="price"]:checked').val();
    if (price) params.set('price', price);
 
    const genders = [];
    $('input[name="gender"]:checked').each(function() {
        genders.push($(this).val());
    });
    if (genders.length) params.set('genders', genders.join(','));
    
    const sizes = [];
    $('input[name="size"]:checked').each(function() {
        sizes.push($(this).val());
    });
    if (sizes.length) params.set('sizes', sizes.join(','));
    
    // Cập nhật URL mà không reload trang
    window.history.pushState({}, '', `${location.pathname}?${params.toString()}`);
}

function updateURLParameter(key, value) {
    const params = new URLSearchParams(window.location.search);
    params.set(key, value);
    window.history.pushState({}, '', `${location.pathname}?${params.toString()}`);
}
function updateActiveFilters() {
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
    if (price) $(`input[name="price"][value="${price}"]`).prop('checked', true);
    
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
    let apiUrl = `../../../user/API/index.php?type=filter&page=${page}`;
    
    // Thêm các tham số filter 
    if (params.get('categories')) apiUrl += `&categories=${params.get('categories')}`;
    if (params.get('price')) apiUrl += `&price=${params.get('price')}`;
    if (params.get('genders')) apiUrl += `&genders=${params.get('genders')}`;
    if (params.get('sizes')) apiUrl += `&sizes=${params.get('sizes')}`;
    
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
        url: `../../../admin/API/index.php?type=getAllProducts&page=${page}`,
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
        html += `
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="../../../${product.Anh } " class="card-img-top" alt="${product.TenSP}">
                <div class="card-body">
                    <h5 class="card-title">${product.TenSP}</h5>
                    <p class="card-text">${product.MoTa}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-danger fw-bold">${formatPrice(product.GiaBan)} đ</span>
                        <button class="btn btn-sm btn-outline-primary">Thêm vào giỏ</button>
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
    if (pagination.total_pages<=1){
        $('#pagination').hide();
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