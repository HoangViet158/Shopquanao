let selectedImages = []
let selectedNewImages = []
function openAddProductModal() {
  if (!actionPermissions.canAdd) {
    Swal.fire({
      icon: "error",
      title: "Không được phép!",
      text: "Bạn không có quyền hạn truy cập chức năng này!",
      confirmButtonText: "Đã hiểu",
    })
    return
  }
  $("#addProductModal").modal("show")
  $("#addProductForm")[0].reset()
  $("#productImage").val("")
  $("#imagePreview").empty()
  $("#productCategory").val("")
  $("#productType").prop("disabled", true).empty().append('<option value="">Chọn danh mục trước</option>')
  selectedImages = []
  selectedNewImages = []
}
function handleProduct(shouldLoadData = true) {
  const Mange_client = document.getElementsByClassName("Mange_client")[0]
  const ProductOut = `
  <div class="container-fluid">
    <div class="toolbar mb-3">
      <div class="input-group" style="width:300px;">
        <input type="text" class="form-control" placeholder="Tìm kiếm......">
        <button class="btn "style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button" onClick="searchProduct()">
          <i class="fas fa-search"></i>
        </button>
      </div>
      <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;"  onclick="openAddProductModal()">
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
                <th>Phân loại</th>
                <th>Giá bán</th>
                <th>Số lượng</th>
                <th>Khuyến mãi</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="productTableBody"></tbody>
          </table>
        </div>
        <div class="pagination justify-content-center mt-3"></div>
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
                  <option value="1">Nam</option>
                  <option value="0">Nữ</option>
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
              <!--
              <div class="col-md-6">
                <label for="editProductPromotion" class="form-label">Khuyến mãi</label>
                <select class="form-select" id="editProductPromotion">
                  <option value="">Không có khuyến mãi</option>
                </select>
              </div>
              !-->
            </div>
            <div class="mb-3">
              <label for="editProductDescription" class="form-label">Mô tả sản phẩm</label>
              <textarea class="form-control" id="editProductDescription" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Ảnh sản phẩm</label>
              <div id="editImagePreview" class="d-flex flex-wrap gap-2 mb-2"></div>
              <input class="form-control" type="file" id="editProductImage" name="newImages[]" multiple accept="image/*">
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
                  <option value="1">Nam</option>
                  <option value="0">Nữ</option>
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
                <label for="productCategory" class="form-label">Phân loại</label>
                <select class="form-select" id="productType" required>
                <option value=""></option>
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
  $("#addProductModal").on("hidden.bs.modal", () => {
    selectedNewImages = []
  })
  $("#editProductModal").on("hidden.bs.modal", () => {
    selectedNewImages = []
    $("#editProductForm").data("deletedImages", [])
  })
  if (shouldLoadData) {
    const urlParams = new URLSearchParams(window.location.search)
    const initialPage = Number.parseInt(urlParams.get("page")) || 1
    loadProductData(initialPage)
    loadCategoriesAndPromotions()
  }
}
function getCurrentPage() {
  const urlParams = new URLSearchParams(window.location.search)
  return Number.parseInt(urlParams.get("page")) || 1
}
function searchProduct() {
  const searchValue = document.querySelector(".input-group input").value.trim()
  if (searchValue === "") {
    loadProductData()
    return
  }
  $.ajax({
    url: `../../admin/API/index.php?type=searchProducts&search=${encodeURIComponent(searchValue)}`,
    type: "GET",
    dataType: "json",
    success: (data) => {
      const tableBody = $("#productTableBody")
      tableBody.empty()

      if (data.length === 0) {
        tableBody.append('<tr><td colspan="8" class="text-center py-4">Không tìm thấy sản phẩm nào!</td></tr>')
      } else {
        renderProductTable(data)
        $(".pagination").empty() // Xóa phân trang trick lỏ
      }
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tìm kiếm:", error)
      alert("Không thể tìm kiếm sản phẩm!")
    },
  })
}
function loadProductData(page = 1) {
  // Đảm bảo page là số
  page = Number.parseInt(page) || 1

  $.ajax({
    url: `../../admin/API/index.php?type=getAllProducts&page=${page}`,
    type: "GET",
    dataType: "json",
    success: (data) => {
      renderProductTable(data.products)
      renderPagination(data.pagination)
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải dữ liệu:", error)
    },
  })
}
function renderPagination(pagination) {
  if (pagination.total_pages <= 1) {
    $(".pagination").empty()
    return
  }
  const paginationHtml = `
    <ul class="pagination justify-content-center">
      ${
        pagination.current_page > 1
          ? `<li class="page-item">
          <a class="page-link" href="#" data-page="${pagination.current_page - 1}">Trước</a>
        </li>`
          : ""
      }
      
      ${Array.from({ length: pagination.total_pages }, (_, i) => {
        const page = i + 1
        // Chỉ hiển thị một số trang gần trang hiện tại
        if (Math.abs(page - pagination.current_page) <= 2 || page === 1 || page === pagination.total_pages) {
          return `
            <li class="page-item ${page === pagination.current_page ? "active" : ""}">
              <a class="page-link" href="#" data-page="${page}">${page}</a>
            </li>
          `
        }
        // Hiển thị ... cho các trang bị ẩn
        if (Math.abs(page - pagination.current_page) === 3) {
          return `<li class="page-item disabled"><span class="page-link">...</span></li>`
        }
        return ""
      }).join("")}
      
      ${
        pagination.current_page < pagination.total_pages
          ? `<li class="page-item">
          <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Sau</a>
        </li>`
          : ""
      }
    </ul>
  `

  $(".pagination").html(paginationHtml)

  // Gắn sự kiện click
  $(".pagination")
    .off("click", ".page-link")
    .on("click", ".page-link", function (e) {
      e.preventDefault()
      const page = Number.parseInt($(this).data("page")) || 1
      loadProductData(page)
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
  $("#productType").prop("disabled", true).empty().append('<option value="">Chọn danh mục trước</option>')
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
  $("#productCategory").on("change", function () {
  console.log("Category changed") 
  const categoryId = $(this).val()
  const typeSelect = $("#productType")

  if (categoryId) {
    // hiện thị thông báo đang tải
    typeSelect.prop("disabled", false).empty().append('<option value="">Đang tải...</option>')
    console.log(categoryId)
    $.ajax({
      url: `../../admin/API/index.php?type=getAllTypeByCategory&id=${categoryId}`,
      type: "GET",
      dataType: "json",
      success: (data) => {
        typeSelect.empty().append('<option value="">Chọn phân loại</option>')
        if (data && data.length > 0) {
          data.forEach((type) => {
            typeSelect.append(`<option value="${type.MaPL}">${type.TenPL}</option>`)
          })
        } else {
          typeSelect.append('<option value="">Không có phân loại</option>')
        }
      },
      error: (xhr, status, error) => {
        console.error("Lỗi khi tải phân loại:", error)
        typeSelect.empty().append('<option value="">Lỗi tải phân loại</option>')
      },
    })
  } else {
    typeSelect.prop("disabled", true).empty().append('<option value="">Chọn danh mục trước</option>')
  }
})
  $("#productImage").change(function () {
    const preview = $("#imagePreview")
    const newFiles = Array.from(this.files)
    console.log(this.files)
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
    const files = this.files
    const preview = $("#editImagePreview")

    // Thêm ảnh mới vào mảng selectedNewImages
    Array.from(files).forEach((file) => {
      selectedNewImages.push(file)

      const reader = new FileReader()
      reader.onload = (e) => {
        // Thêm thuộc tính data-temp-id để phân biệt ảnh mới
        const tempId = "temp_" + Math.random().toString(36).substr(2, 9)
        preview.append(`
          <div class="position-relative" style="width:100px; height:100px;">
            <img src="${e.target.result}" class="img-thumbnail" 
                 style="width:100%;height:100%; object-fit: cover;">
            <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                    onclick="removeImage('${tempId}')" data-temp-id="${tempId}"></button>
          </div>
        `)
      }
      reader.readAsDataURL(file)
    })

    // Reset input file
    // $(this).val('');
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
  const productType = $("#productType").val()
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
  // Validate product type
  if (!productType) {
    isValid = false
    $("#productType").addClass("is-invalid").after(`<div class="invalid-feedback">Vui lòng chọn phân loại.</div>`)
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
  formData.append("MaPL", productType)
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
      Swal.fire({
        icon: "success",
        title: "Thành công!",
        text: "Thêm sản phẩm thành công!",
        confirmButtonText: "Đã hiểu",
      })
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
  console.log(products)
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
              <td><img src="../..${product.Anh[0]}" alt="Ảnh" width="60" height="60" style="object-fit: cover; border-radius: 5px;"></td>
              <td>${product.DanhMuc.TenDM}</td>
              <td>${product.PhanLoai.TenPL}</td>
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
function deleteProduct(productId) {
  if (!actionPermissions.canDelete) {
    Swal.fire({
      icon: "error",
      title: "Không được phép!",
      text: "Bạn không có quyền hạn truy cập chức năng này!",
      confirmButtonText: "Đã hiểu",
    });
    return;
  }

  Swal.fire({
    title: "Bạn có chắc chắn muốn xóa?",
    text: "Sản phẩm sẽ bị xóa ",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xóa",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      const url = `../../admin/API/index.php?type=deleteProduct&MaSP=${productId}`;

      $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: (response) => {
          if (response.success.success===true) {
            Swal.fire({
              icon: "success",
              title: "Thành công!",
              text: response.success.message ,
            });
            loadProductData();
          } else {
            Swal.fire({
              icon: "error",
              title: "Lỗi!",
              text: response.success.message ,
            });
          }
        },
        error: (xhr, status, error) => {
          Swal.fire({
            icon: "error",
            title: "Lỗi hệ thống!",
            text: "Lỗi khi xóa sản phẩm: " + error,
          });
          console.log(xhr.responseText);
        },
      });
    }
  });
}
function showEditForm(productId) {
  $("#editProductImage").val("")
  if (!actionPermissions.canEdit) {
    Swal.fire({
      icon: "error",
      title: "Không được phép!",
      text: "Bạn không có quyền hạn truy cập chức năng này!",
      confirmButtonText: "Đã hiểu",
    })
    return
  }

  // dùng when vs then để load đồng thời danh sách danh mục, khuyến mãi và thông tin sản phẩm
  $.when(
    $.ajax({
      url: "../../admin/API/index.php?type=getAllCategories",
      type: "GET",
      dataType: "json",
    }),
    $.ajax({
      url: "../../admin/API/index.php?type=getAllPromotions",
      type: "GET",
      dataType: "json",
    }),
    $.ajax({
      url: `../../admin/API/index.php?type=getProductById&id=${productId}`,
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

      $("#editProductType").closest(".col-md-6").remove();
      const typeFormGroup = $(
        '<div class="col-md-6 "><label for="editProductType" class="form-label">Phân loại</label></div>',
      )
      const typeSelect = $('<select class="form-select" id="editProductType" required disabled></select>')
      typeFormGroup.append(typeSelect)
      $("#editProductCategory").closest(".row").append(typeFormGroup)

      // tạo option mặc định cho phân loại
      typeSelect.empty().append('<option value="">Chọn danh mục trước</option>')

      // nếu sản phẩm có phân loại thì tải danh sách phân loại theo danh mục
      if (product.MaDM) {
        typeSelect.prop("disabled", false).empty().append('<option value="">Đang tải...</option>')

        $.ajax({
          url: `../../admin/API/index.php?type=getAllTypeByCategory&id=${product.MaDM}`,
          type: "GET",
          dataType: "json",
          success: (types) => {
            typeSelect.empty().append('<option value="">Chọn phân loại</option>')
            if (types && types.length > 0) {
              types.forEach((type) => {
                const selected = type.MaPL == product.MaPL ? "selected" : ""
                typeSelect.append(`<option value="${type.MaPL}" ${selected}>${type.TenPL}</option>`)
              })
            } else {
              typeSelect.append('<option value="">Không có phân loại</option>')
            }
          },
          error: (xhr, status, error) => {
            console.error("Lỗi khi tải phân loại:", error)
            typeSelect.empty().append('<option value="">Lỗi tải phân loại</option>')
          },
        })
      }

      // gán sự kiện change cho danh mục để tải lại phân loại
      categorySelect.on("change", function () {
        const categoryId = $(this).val()

        if (categoryId) {
          typeSelect.prop("disabled", false).empty().append('<option value="">Đang tải...</option>')

          $.ajax({
            url: `../../admin/API/index.php?type=getAllTypeByCategory&id=${categoryId}`,
            type: "GET",
            dataType: "json",
            success: (data) => {
              typeSelect.empty().append('<option value="">Chọn phân loại</option>')
              if (data && data.length > 0) {
                data.forEach((type) => {
                  typeSelect.append(`<option value="${type.MaPL}">${type.TenPL}</option>`)
                })
              } else {
                typeSelect.append('<option value="">Không có phân loại</option>')
              }
            },
            error: (xhr, status, error) => {
              console.error("Lỗi khi tải phân loại:", error)
              typeSelect.empty().append('<option value="">Lỗi tải phân loại</option>')
            },
          })
        } else {
          typeSelect.prop("disabled", true).empty().append('<option value="">Chọn danh mục trước</option>')
        }
      })
      promotionSelect.val(product.MaKM || "")

      // Hiển thị ảnh hiện tại
      const preview = $("#editImagePreview")
      preview.empty()

      // Reset danh sách ảnh mới và ảnh xóa
      selectedNewImages = []
      $("#editProductForm").data("deletedImages", [])

      product.Anh.forEach((image) => {
        preview.append(`
      <div class="position-relative" style="width:100px; height:100px;">
        <img src="../..${image.Url}" class="img-thumbnail" style="width:100%;height:100%; object-fit: cover;">
        <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" 
                onclick="removeImage('${image.MaAnh}')"></button>
      </div>
    `)
      })
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
  formData.append("MaPL", $("#editProductType").val())
  formData.append("MaKM", $("#editProductPromotion").val() || "null")
  formData.append("GioiTinh", $("#editProductGender").val())
  formData.append("MoTa", $("#editProductDescription").val())

  // Thêm ảnh mới từ danh sách đã chọn
  selectedNewImages.forEach((file) => {
    formData.append("newImages[]", file)
  })

  // Thêm ảnh cần xóa
  const deletedImages = $("#editProductForm").data("deletedImages") || []
  deletedImages.forEach((id) => {
    formData.append("deletedImages[]", id)
  })

  $.ajax({
    url: "../../admin/API/index.php?type=updateProduct",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",
    success: (response) => {
      if (response.success) {
        Swal.fire({
          icon: "success",
          title: "Thành công!",
          text: "Cập nhật sản phẩm thành công!",
          confirmButtonText: "Đã hiểu",
        })
        $("#editProductModal").modal("hide")
        loadProductData()
        // Reset sau khi thành công
        selectedNewImages = []
      } else {
        alert("Lỗi: " + (response.message || "Không thể cập nhật sản phẩm"))
      }
    },
    error: (xhr, status, error) => {
      alert("Lỗi khi cập nhật: " + error)
    },
  })
}
function removeImage(id) {
  if (confirm("Bạn có chắc muốn xóa ảnh này?")) {
    if (id.startsWith("temp_")) {
      // Xóa ảnh mới (không có ID thật)
      const index = selectedNewImages.findIndex((_, i) => $(`button[data-temp-id="${id}"]`).length > 0)
      if (index !== -1) {
        selectedNewImages.splice(index, 1)
      }
      $("#editProductImage").val("")
      $(`button[onclick*="${id}"]`).parent().remove()
    } else {
      // Xóa ảnh cũ (có ID)
      const deletedImages = $("#editProductForm").data("deletedImages") || []
      deletedImages.push(id)
      $("#editProductForm").data("deletedImages", deletedImages)
      $(`button[onclick*="${id}"]`).parent().hide()
    }
  }
}
function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount)
}
handleProduct()

document.addEventListener('DOMContentLoaded', () => {
        const permissionElement = document.getElementById('product-permissions');
        const actionPermissions = {
        canView: permissionElement.dataset.canView ,
        canEdit: permissionElement.dataset.canEdit ,
        canDelete: permissionElement.dataset.canDelete ,
        canAdd: permissionElement.dataset.canAdd
    };
    console.log("Can Delete:", actionPermissions.canDelete);
})