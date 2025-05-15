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
  // console.log('section-products found:', $('.section-products').length);
  // console.log('products:', products);
  // console.log('isArray:', Array.isArray(products));
  // console.log('length:', products ? products.length : 'null');
  const container = $('.section-products');
  container.empty();

  // Nếu products là object, chuyển thành array
  if (products && !Array.isArray(products)) {
    products = Object.values(products);
  }

  if (!products || products.length === 0) {
    container.append('<p>Không có sản phẩm.</p>');
    return;
  }

  products.forEach(product => {
    const rawImage = product.Anh.length > 0 ? product.Anh[0] : '';
    const firstImage = rawImage ? `/web2${rawImage}` : '/web2/images/no-image.png';

    //console.log("Raw image:", rawImage);
    //console.log("First image URL:", firstImage);

    const productHtml = `
      <div class="product-item">
          <div><img src="${firstImage}" class="card-img-top" alt="${product.TenSP}" onerror="this.src='/web2/images/no-image.png'"></div>
          <div class="product-name">${product.TenSP}</div>
          <div class="product-desc">${product.MoTa}</div>
          <div class="product-price">${product.GiaBan}</div>
      </div>
    `;

    container.append(productHtml);
  });
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

            randomProducts.forEach(product => {
                const imageUrl = product.Anh?.[0] ? `/web2${product.Anh[0]}` : '/web2/images/no-image.png';

                container.append(`
                    <div class="product-item">
                        <img src="${imageUrl}" alt="${product.TenSP}" class="card-img-top" onerror="this.src='/web2/images/no-image.png'">
                        <div class="product-name">${product.TenSP}</div>
                        <p>${product.MoTa}</p>
                        <p style="color:red; font-weight:bold">${product.GiaBan}</p>
                    </div>
                `);
            });
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi lấy sản phẩm:", error);
        }
    });
}