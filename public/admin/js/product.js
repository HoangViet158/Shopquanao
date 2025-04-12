function handleProduct() {
    const Mange_client = document.getElementsByClassName("Mange_client")[0];
    const ProductOut = `
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="mb-0">Quản lý sản phẩm</h3>
          
        </div>
        
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>#ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Số lượng</th>
                    <th>Khuyến mãi</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody id="productTableBody">
                  <!-- Dữ liệu sẽ được thêm vào đây -->
                </tbody>
              </table>
            </div>
            
            <nav aria-label="Page navigation" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    `;
    Mange_client.innerHTML = ProductOut;

    // Gọi AJAX để lấy dữ liệu sản phẩm
    $.ajax({
        url: '../../admin/api/index.php?type=getAllProducts',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            renderProductTable(data);
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi tải dữ liệu:", error);
            alert("Không thể tải dữ liệu sản phẩm!");
        }
    });
}

function renderProductTable(products) {
    const tableBody = $('#productTableBody');
    tableBody.empty();
    
    if (products.length === 0) {
        tableBody.append('<tr><td colspan="8" class="text-center py-4">Không có sản phẩm nào!</td></tr>');
        return;
    }

    $.each(products, function(index, product) {
        const promoText = product.KhuyenMai.TenKM === "Không có" ? "" : product.KhuyenMai.TenKM;
        const stockClass = product.SoLuong <= 0 ? "text-danger" : "";
        
        const row = `
            <tr>
                <td>#${product.MaSP}</td>
                <td>${product.TenSP}</td>
                <td><img src="../../${product.Anh[0]}" alt="Ảnh" width="60" height="60" style="object-fit: cover; border-radius: 5px;"></td>
                <td>${product.DanhMuc.TenDM}</td>
                <td>${formatCurrency(product.GiaBan)}</td>
                <td class="${stockClass}">${product.SoLuong}</td>
                <td>${promoText}</td>
                <td>
                    <button class="btn btn-sm btn-outline-success me-2">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBody.append(row);
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}