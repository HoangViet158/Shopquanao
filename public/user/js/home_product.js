let currentPage = 1;
const perPage = 8;
$(document).ready(function () {
     loadProductData();
     loadRandomProducts()
});
function loadProductData() {
  $.ajax({
    url: '../../admin/API/index.php?type=getAllProducts&page=1&perPage=8',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      console.log("Dữ liệu nhận được:", data); // debug
      renderProducts(data.products);
    },
    error: function(xhr, status, error) {
      console.error("Lỗi khi lấy dữ liệu sản phẩm:", error);
    }
  });
}

function renderProducts(products) {
  const container = $('.section-products');
  container.empty();

  if (products && !Array.isArray(products)) {
    products = Object.values(products);
  }

  if (!products || products.length === 0) {
    container.append('<p>Không có sản phẩm.</p>');
    return;
  }

  let rowHtml = '<div class="row justify-content-center">';

  products.forEach((product, index) => {
    const rawImage = product.Anh.length > 0 ? product.Anh[0] : '';
    const firstImage = rawImage ? `../..${rawImage}` : '../../images/no-image.png';

    const productHtml = `
      <div class="col-3 mb-4">
        <div class="product-item card h-100 text-center">
          <a href="product_detail.php?id=${product.MaSP}">
              <img src="${firstImage}" class="card-img-top" alt="${product.TenSP}">
          </a>
          <div class="card-body">
            <div class="product-name font-weight-bold">${product.TenSP}</div>
            <div class="product-desc text-muted">${product.MoTa}</div>
            <div class="product-price text-danger font-weight-bold mt-2">${product.GiaBan}đ</div>
          </div>
        </div>
      </div>
    `;

    rowHtml += productHtml;

    if ((index + 1) % 4 === 0 && index !== products.length - 1) {
      rowHtml += '</div><div class="row justify-content-st">';
    }
  });

  rowHtml += '</div>';
  container.append(rowHtml);
}



function loadRandomProducts() {
    $.ajax({
        url: '../../admin/API/index.php?type=getAllProducts',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (!Array.isArray(data.products) || data.products.length === 0) {
                console.warn("Không có sản phẩm.");
                return;
            }

            // Xáo trộn và lấy 4 sản phẩm ngẫu nhiên
            const randomProducts = data.products.sort(() => Math.random() - 0.5).slice(0, 4);
            const container = $('.product-grid'); // nơi hiển thị
            container.empty();

            let rowHtml = '<div class="row justify-content-center">';

            randomProducts.forEach((product, index) => {
                const imageUrl = product.Anh?.[0] ? `../..${product.Anh[0]}` : '../../images/no-image.png';

                const productHtml = `
                    <div class="col-3 mb-4">
                        <div class="product-item card h-100 text-center">
                          <a href="product_detail.php?id=${product.MaSP}">
                              <img src="${imageUrl}" class="card-img-top" alt="${product.TenSP}">
                          </a>
                            <div class="card-body">
                                <div class="product-name font-weight-bold">${product.TenSP}</div>
                                <div class="product-desc text-muted">${product.MoTa}</div>
                                <div class="product-price text-danger font-weight-bold mt-2">${product.GiaBan}đ</div>
                            </div>
                        </div>
                    </div>
                `;

                rowHtml += productHtml;

                if ((index + 1) % 4 === 0 && index !== randomProducts.length - 1) {
                    rowHtml += '</div><div class="row justify-content-center">';
                }
            });

            rowHtml += '</div>';
            container.append(rowHtml);
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi lấy sản phẩm:", error);
        }
    });
}
