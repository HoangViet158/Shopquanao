let selectedImages = []
function handleProduct() {
  const Mange_client = document.getElementsByClassName("Mange_client")[0]
  const ProductOut = `
  <div class="container-fluid">
    <div class="toolbar mb-3">
      <div class="input-group" style="width:300px;">
        <input type="text" class="form-control" placeholder="Tìm kiếm......">
        <button class="btn "style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button">
          <i class="fas fa-search"></i>
        </button>
      </div>
      <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;" data-bs-toggle="modal" data-bs-target="#addProductModal">
      <i class="fas fa-plus"></i>
        <span>Thêm mới</span>
      </button>
    </div>
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class=" table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Ảnh</th>
                <th>Danh mục</th>
                <th>Giá bán</th>
                <th>Số lượng</th>
                <th>Khuyến mãi</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="productTableBody"></tbody>
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
    <!-- ... (phần sửa sản phẩm ... -->
       <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProductForm">
            <input type="hidden" id="editMaSP">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="editProductName" class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" id="editProductName" required>
              </div>
              <div class="col-md-6">
                <label for="editProductGender" class="form-label">Giới tính</label>
                <select class="form-select" id="editProductGender" required>
                  <option value="0">Nam</option>
                  <option value="1">Nữ</option>
                  <option value="2">Unisex</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="editProductCategory" class="form-label">Danh mục</label>
                <select class="form-select" id="editProductCategory" required>
                  <option value="">Chọn danh mục</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="editProductPromotion" class="form-label">Khuyến mãi</label>
                <select class="form-select" id="editProductPromotion">
                  <option value="">Không có khuyến mãi</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="editProductDescription" class="form-label">Mô tả sản phẩm</label>
              <textarea class="form-control" id="editProductDescription" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Ảnh sản phẩm</label>
              <div id="editImagePreview" class="d-flex flex-wrap gap-2 mb-2"></div>
              <input class="form-control" type="file" id="editProductImage" multiple accept="image/*">
              <small class="text-muted">Chọn ảnh mới để thay thế (nếu cần)</small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" onclick="updateProduct()">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  </div>
    <!-- ... phần thêm sp ... -->
    <div class="modal fade" id="addProductModal" enctype="multipart/form-data" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form id="addProductForm">
            <div class="row mb-3">
              <div class="col-md-6">
              <label for="productName" class="form-label">Tên sản phẩm</label>
              <input type="text" class="form-control" id="productName" required>
              </div>
              <div class="col-md-6">
              <label for="productGender" class="form-label">Giới tính</label>
              <select class="form-select" id="productGender" required>
                <option value="0">Nam</option>
                <option value="1">Nữ</option>
                <option value="2">Unisex</option>
              </select>
            </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="productCategory" class="form-label">Danh mục</label>
                <select class="form-select" id="productCategory" required>
                <option value=""></option>
                </select>
              </div>
              <div class="col-md-6">
              <label for="productPromotion" class="form-label">Khuyến mãi</label>
                <select class="form-select" id="productPromotion">
                  <option value="">Không có khuyến mãi</option>
                </select>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="productDescription" class="form-label">Mô tả sản phẩm</label>
              <textarea class="form-control" id="productDescription" rows="3"></textarea>
            </div>

            <div class="mb-3">
              <label for="productImage" class="form-label">Ảnh sản phẩm</label>
              <input class="form-control" type="file" id="productImage" multiple accept="image/*">
              <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
            
            </div>

          </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="button" class="btn btn-primary" onclick="submitProductForm()">Lưu sản phẩm</button>
            
            </div>
        </div>
      </div>
    </div>
  </div>
  `
  Mange_client.innerHTML = ProductOut

  loadProductData()
  loadCategoriesAndPromotions()
}
function loadProductData() {
  $.ajax({
    url: "../../admin/api/index.php?type=getAllProducts",
    type: "GET",
    dataType: "json",
    success: (data) => {
      renderProductTable(data)
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải dữ liệu:", error)
      alert("Không thể tải dữ liệu sản phẩm!")
    },
  })
}
function loadCategoriesAndPromotions() {
  $.ajax({
    url: "../../admin/API/index.php?type=getAllCategories",
    type: "GET",
    dataType: "json",
    success: (data) => {
      const select = $("#productCategory")
      data.forEach((category) => {
        select.append(`<option value="${category.MaDM}">${category.TenDM}</option>`)
      })
    },
  })
  $.ajax({
    url: "../../admin/API/index.php?type=getAllPromotions",
    type: "GET",
    dataType: "json",
    success: (data) => {
      const select = $("#productPromotion")
      data.forEach((promo) => {
        select.append(`<option value=${promo.MaKM}>${promo.TenKM}</option>`)
      })
    },
  })
  $("#productImage").change(function () {
    const preview = $("#imagePreview")
    const newFiles = Array.from(this.files)

    // Cộng dồn ảnh mới vào danh sách cũ
    selectedImages = selectedImages.concat(newFiles)
    preview.empty()

    selectedImages.forEach((file, index) => {
      const reader = new FileReader()
      reader.onload = (e) => {
        preview.append(`
          <div class="position-relative" style="width:100px; height:100px;">
            <img src="${e.target.result}" class="img-thumbnail" 
                 style="width:100%;height:100%; object-fit: cover;">
            <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                    onclick="removeImagePreview(${index})"></button>
          </div>
        `)
      }
      reader.readAsDataURL(file)
    })
  })
  $("#editProductImage").change(function () {
    const preview = $("#editImagePreview")
    const files = Array.from(this.files)

    files.forEach((file, index) => {
      const reader = new FileReader()
      reader.onload = (e) => {
        preview.append(`
          <div class="position-relative" style="width:100px; height:100px;">
            <img src="${e.target.result}" class="img-thumbnail" 
                 style="width:100%;height:100%; object-fit: cover;">
            <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                    onclick="removeNewImage(this)"></button>
          </div>
        `)
      }
      reader.readAsDataURL(file)
    })
  })
}
function removeImagePreview(index) {
  selectedImages.splice(index, 1)
  $("#productImage").val("")
  $("#imagePreview").empty()

  selectedImages.forEach((file, i) => {
    const reader = new FileReader()
    reader.onload = (e) => {
      $("#imagePreview").append(`
        <div class="position-relative" style="width:100px; height:100px;">
          <img src="${e.target.result}" class="img-thumbnail" 
               style="width:100%;height:100%; object-fit: cover;">
          <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                  onclick="removeImagePreview(${i})"></button>
        </div>
      `)
    }
    reader.readAsDataURL(file)
  })
}

function submitProductForm() {
  $(".is-invalid").removeClass("is-invalid")
  $(".invalid-feedback").remove()

  let isValid = true

  const name = $("#productName").val().trim()
  const category = $("#productCategory").val()
  const promotion = $("#productPromotion").val()
  const gender = $("#productGender").val()
  const description = $("#productDescription").val().trim()

  // Validate tên sản phẩm
  if (!name) {
    isValid = false
    $("#productName").addClass("is-invalid").after(`<div class="invalid-feedback">Vui lòng nhập tên sản phẩm.</div>`)
  }

  // Validate mô tả
  if (!description) {
    isValid = false
    $("#productDescription")
      .addClass("is-invalid")
      .after(`<div class="invalid-feedback">Vui lòng nhập mô tả sản phẩm.</div>`)
  }

  // Validate danh mục
  if (!category) {
    isValid = false
    $("#productCategory").addClass("is-invalid").after(`<div class="invalid-feedback">Vui lòng chọn danh mục.</div>`)
  }

  // Validate ảnh
  if (selectedImages.length === 0) {
    isValid = false
    $("#productImage").addClass("is-invalid")
    if ($("#productImage").next(".invalid-feedback").length === 0) {
      $("#productImage").after(`<div class="invalid-feedback d-block">Vui lòng chọn ít nhất một ảnh.</div>`)
    }
  }
  if (!isValid) return
  const formData = new FormData()
  formData.append("TenSP", name)
  formData.append("MaDM", category)
  formData.append("MaKM", promotion === "" ? null : promotion) // null nếu không chọn khuyến mãi
  formData.append("GioiTinh", gender)
  formData.append("MoTa", description)
  formData.append("SoLuong", 0)
  formData.append("GiaBan", 0)
  selectedImages.forEach((file, i) => {
    formData.append("image[]", file)
  })

  $.ajax({
    url: "../../admin/API/index.php?type=addProduct",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: (response) => {
      alert("Thêm sản phẩm thành công!")
      $("#addProductModal").modal("hide")
      loadProductData()
      $("#addProductForm")[0].reset()
      $("#imagePreview").empty()
      selectedImages = []
    },
    error: (xhr, status, error) => {
      alert("Có lỗi xảy ra: " + error)
    },
  })
}

function renderProductTable(products) {
  const tableBody = $("#productTableBody")
  tableBody.empty()

  if (products.length === 0) {
    tableBody.append('<tr><td colspan="8" class="text-center py-4">Không có sản phẩm nào!</td></tr>')
    return
  }

  products.forEach((product) => {
    const row = `
          <tr>
              <td>${product.MaSP}</td>
              <td>${product.TenSP}</td>
              <td><img src="../../${product.Anh[0]}" alt="Ảnh" width="60" height="60" style="object-fit: cover; border-radius: 5px;"></td>
              <td>${product.DanhMuc.TenDM}</td>
              <td>${formatCurrency(product.GiaBan)}</td>
              <td class="${product.SoLuong <= 0 ? "text-danger" : ""}">${product.SoLuong}</td>
              <td>${product.KhuyenMai.TenKM === "Không có" ? "" : product.KhuyenMai.TenKM}</td>
              <td>
                  <button class="btn btn-sm btn-outline-success me-2" onclick="showEditForm(${product.MaSP})">
                      <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${product.MaSP})">
                      <i class="fas fa-trash"></i>
                  </button>
              </td>
          </tr>
      `
    tableBody.append(row)
  })
}
function showEditForm(productId) {
  // dùng when vs then để load đồng thời danh sách danh mục, khuyến mãi và thông tin sản phẩm
  $.when(
    $.ajax({
      url: "../../admin/api/index.php?type=getAllCategories",
      type: "GET",
      dataType: "json",
    }),
    $.ajax({
      url: "../../admin/api/index.php?type=getAllPromotions",
      type: "GET",
      dataType: "json",
    }),
    $.ajax({
      url: `../../admin/api/index.php?type=getProductById&id=${productId}`,
      type: "GET",
      dataType: "json",
    }),
  )
    .then((categoriesResponse, promotionsResponse, productResponse) => {
      const categories = categoriesResponse[0]
      const promotions = promotionsResponse[0]
      const product = productResponse[0]

      const categorySelect = $("#editProductCategory")
      categorySelect.empty().append('<option value="">Chọn danh mục</option>')
      categories.forEach((category) => {
        categorySelect.append(`<option value="${category.MaDM}">${category.TenDM}</option>`)
      })
      const promotionSelect = $("#editProductPromotion")
      promotionSelect.empty().append('<option value="">Không có khuyến mãi</option>')
      promotions.forEach((promo) => {
        promotionSelect.append(`<option value="${promo.MaKM}">${promo.TenKM}</option>`)
      })
      $("#editMaSP").val(product.MaSP)
      $("#editProductName").val(product.TenSP)
      $("#editProductDescription").val(product.MoTa)
      $("#editProductGender").val(product.GioiTinh)
      categorySelect.val(product.MaDM)
      promotionSelect.val(product.MaKM || "")

      // Hiển thị ảnh hiện tại
      const preview = $("#editImagePreview")
      preview.empty()
      product.Anh.forEach((image) => {
        preview.append(`
    <div class="position-relative" style="width:100px; height:100px;">
      <img src="../../${image.Url}" class="img-thumbnail" style="width:100%;height:100%; object-fit: cover;">
      <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
              onclick="removeEditImage('${image.MaAnh}')" data-image-id="${image.MaAnh}"></button>
    </div>
  `)
      })

      // Hiển thị modal
      $("#editProductModal").modal("show")
    })
    .fail((xhr, status, error) => {
      alert("Không thể tải thông tin sản phẩm: " + error)
    })
}

function updateProduct() {
  const productId = $("#editMaSP").val()
  if (!productId) {
    alert("Không tìm thấy ID sản phẩm!")
    return
  }

  const formData = new FormData()
  formData.append("MaSP", productId)
  formData.append("TenSP", $("#editProductName").val())
  formData.append("MaDM", $("#editProductCategory").val())
  formData.append("MaKM", $("#editProductPromotion").val() || "null")
  formData.append("GioiTinh", $("#editProductGender").val())
  formData.append("MoTa", $("#editProductDescription").val())
  const files = $('#editProductImage')[0].files;
  for (let i = 0; i < files.length; i++) {
    formData.append('newImages[]', files[i]); 
  }
  const deletedImages = $('#editProductForm').data('deletedImages') || [];
  deletedImages.forEach(id => {
    formData.append('deletedImages[]', id);
  });

  $.ajax({
    url: "../../admin/api/index.php?type=updateProduct",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: (response) => {
      if (response.success) {
        alert("Cập nhật sản phẩm thành công!")
        $("#editProductModal").modal("hide")
        loadProductData()
      } else {
        alert("Lỗi: " + (response.message || "Không thể cập nhật sản phẩm"))
      }
    },
    error: (xhr, status, error) => {
      alert("Lỗi khi cập nhật: " + error)
    },
  })
}
function removeEditImage(imageId) {
  if (confirm("Bạn có chắc muốn xóa ảnh này?")) {
    const deletedImages = $("#editProductForm").data("deletedImages") || []
    if (!deletedImages.includes(imageId)) {
      deletedImages.push(imageId)
      $("#editProductForm").data("deletedImages", deletedImages)
    }
    $(`button[onclick*="${imageId}"]`).parent().remove()
  }
}

function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount)
}
