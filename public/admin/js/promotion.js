function handlePromotions() {
  const Mange_client = document.getElementsByClassName("Mange_client")[0]
  const PromotionOut = `
  <div class="container-fluid">
    <div class="toolbar mb-3">
      <div class="input-group" style="width:300px;">
        <input type="text" class="form-control" placeholder="Tìm kiếm......">
        <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button" onClick="searchProduct()">
          <i class="fas fa-search"></i>
        </button>
      </div>
      <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;" onclick="openAddPromotionModal()">
      <i class="fas fa-plus"></i>
        <span>Thêm mới</span>
      </button>
    </div>
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Tên khuyến mãi</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Giá trị khuyến mãi</th>
                <th>Trạng thái</th> 
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="promotionTableBody"></tbody>
          </table>
        </div>
        <div class="pagination justify-content-center mt-3"></div>
      </div>
    </div>
    <!-- Modal thêm khuyến mãi -->
    <div class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addPromotionLabel">Thêm khuyến mãi mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addPromotionForm">
              <!-- Phần thông báo lỗi -->
              <div id="promotionAlerts"></div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="promotionName" class="form-label">Tên Khuyến mãi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="promotionName" required>
                </div>
                <div class="col-md-6">
                  <label for="promotionValue" class="form-label">Giá trị khuyến mãi (%) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" class="form-control" id="promotionValue" min="1" max="100" required>
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="promotionStartDate" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="promotionStartDate" required>
                </div>
                <div class="col-md-6">
                  <label for="promotionEndDate" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="promotionEndDate" required>
                </div>
              </div>
              
              <!-- Phần chọn sản phẩm -->
              <div class="row mb-3">
                <div class="col-md-8">
                  <label for="productSelect" class="form-label">Chọn sản phẩm</label>
                  <select class="form-select" id="productSelect">
                    <option value="">-- Chọn sản phẩm --</option>
                  </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="button" class="btn btn-primary w-100" onclick="addSelectedProduct()">
                    <i class="fas fa-plus me-2"></i>Thêm
                  </button>
                </div>
              </div>
              
              <!-- Danh sách sản phẩm đã chọn -->
              <div class="card">
                <div class="card-header bg-light">
                  <h6 class="mb-0">Danh sách sản phẩm áp dụng</h6>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th width="50%">Tên sản phẩm</th>
                          <th width="30%">Giá bán hiện tại</th>
                          <th width="20%">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="selectedProductsList"></tbody>
                    </table>
                  </div>
                  <div id="profitWarnings" class="p-3"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary" onclick="submitPromotionForm()">Lưu khuyến mãi</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Template row cho sản phẩm đã chọn (hidden) -->
    <template id="productRowTemplate">
      <tr data-product-id="">
        <td class="product-name"></td>
        <td class="product-price"></td>
        <td>
          <button type="button" class="btn btn-sm btn-danger" onclick="removeSelectedProduct(this)">
            <i class="fas fa-trash-alt"></i> Xóa
          </button>
        </td>
      </tr>
    </template>
    <!-- Modal sửa khuyến mãi -->
    <div class="modal fade" id="editPromotionModal" tabindex="-1" aria-labelledby="editPromotionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPromotionLabel">Sửa khuyến mãi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editPromotionForm">
              <input type="hidden" id="editPromotionId">
              <!-- Phần thông báo lỗi -->
              <div id="editPromotionAlerts"></div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="editPromotionName" class="form-label">Tên Khuyến mãi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="editPromotionName" required>
                </div>
                <div class="col-md-6">
                  <label for="editPromotionValue" class="form-label">Giá trị khuyến mãi (%) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" class="form-control" id="editPromotionValue" min="1" max="100" required>
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="editPromotionStartDate" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="editPromotionStartDate" required>
                </div>
                <div class="col-md-6">
                  <label for="editPromotionEndDate" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="editPromotionEndDate" required>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="editPromotionStatus" class="form-label">Trạng thái</label>
                  <select class="form-select" id="editPromotionStatus">
                    <option value="1">Đang hoạt động</option>
                    <option value="0">Đã kết thúc</option>
                  </select>
                </div>
              </div>
              
              <!-- Phần chọn sản phẩm -->
              <div class="row mb-3">
                <div class="col-md-8">
                  <label for="editProductSelect" class="form-label">Thêm sản phẩm</label>
                  <select class="form-select" id="editProductSelect">
                    <option value="">-- Chọn sản phẩm --</option>
                  </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <button type="button" class="btn btn-primary w-100" onclick="addProductToEditPromotion()">
                    <i class="fas fa-plus me-2"></i>Thêm
                  </button>
                </div>
              </div>
              
              <!-- Danh sách sản phẩm đã áp dụng -->
              <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">Danh sách sản phẩm áp dụng</h6>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th width="50%">Tên sản phẩm</th>
                          <th width="30%">Giá bán hiện tại</th>
                          <th width="20%">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="editSelectedProductsList"></tbody>
                    </table>
                  </div>
                  <div id="editProfitWarnings" class="p-3"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary" onclick="updatePromotion()">Cập nhật</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Xác nhận xóa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="deleteMessage">Bạn có chắc chắn muốn xóa khuyến mãi này?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-danger" onclick="deletePromotion()">Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  `
  Mange_client.innerHTML = PromotionOut
  loadPromotionList()
  loadProductOptions()
}

// Thêm sản phẩm vào danh sách đã chọn
function addSelectedProduct() {
  const select = $("#productSelect")
  const productId = select.val()
  const productName = select.find("option:selected").text()
  const productPrice = select.find("option:selected").data("price")

  if (!productId) {
    showPromotionAlert("warning", "Vui lòng chọn sản phẩm")
    return
  }

  if ($(`#selectedProductsList tr[data-product-id="${productId}"]`).length > 0) {
    showPromotionAlert("warning", "Sản phẩm đã có trong danh sách")
    return
  }

  const template = document.getElementById("productRowTemplate").content.cloneNode(true)
  const row = template.querySelector("tr")
  row.setAttribute("data-product-id", productId)
  row.querySelector(".product-name").textContent = productName
  row.querySelector(".product-price").textContent = formatCurrency(productPrice)

  document.getElementById("selectedProductsList").appendChild(template)

  const discountValue = $("#promotionValue").val()
  if (discountValue) {
    checkProductProfit(productId, discountValue)
  }

  select.val("")
}

// Xóa sản phẩm khỏi danh sách đã chọn
function removeSelectedProduct(button) {
  const row = $(button).closest("tr")
  const productId = row.data("product-id")
  row.remove()

  // Xóa cảnh báo lỗ vốn nếu có
  $(`#profitWarnings .alert[data-product="${productId}"]`).remove()
}

// Tải danh sách sản phẩm cho dropdown
function loadProductOptions() {
  $.ajax({
    url: "../../admin/api/index.php?type=getAllProductsForPromotion",
    method: "GET",
    dataType: "json",
    success: (data) => {
      if (data.success) {
        const select = $("#productSelect")
        select.empty()
        select.append('<option value="">-- Chọn sản phẩm --</option>')
        data.data.forEach((product) => {
          select.append(`<option value="${product.MaSP}" data-price="${product.GiaBan}">${product.TenSP}</option>`)
        })
      }
    },
    error: (error) => {
      console.error("Error loading products:", error)
    },
  })
}

// Mở modal thêm khuyến mãi
function openAddPromotionModal() {
  $("#addPromotionForm")[0].reset()
  $("#selectedProductsList").empty()
  $("#promotionAlerts").empty()

  // Set ngày mặc định
  const today = new Date().toISOString().split("T")[0]
  $("#promotionStartDate").val(today)
  $("#addPromotionModal").modal("show")
}

// Tải danh sách khuyến mãi
function loadPromotionList() {
  $.ajax({
    url: "../../admin/api/index.php?type=getAllPromotions",
    method: "GET",
    dataType: "json",
    success: (data) => {
      renderPromotionList(data)
    },
    error: (error) => {
      console.error("Error loading promotions:", error)
    },
  })
}

// Hiển thị danh sách khuyến mãi
function renderPromotionList(data) {
  const promotionTableBody = $("#promotionTableBody")
  promotionTableBody.empty()

  if (data.length === 0) {
    promotionTableBody.append('<tr><td colspan="7" class="text-center py-4">Không có khuyến mãi nào!</td></tr>')
    return
  }

  data.forEach((promotion) => {
    const row = `
      <tr>
        <td>${promotion.MaKM}</td>
        <td>${promotion.TenKM}</td>
        <td>${formatDate(promotion.NgayBatDau)}</td>
        <td>${formatDate(promotion.NgayKetThuc)}</td>
        <td>${promotion.giaTriKM}%</td>
        <td>${getStatusText(promotion.TrangThai)}</td>
        <td>
          <button class="btn btn-sm btn-outline-success me-2" onclick="editPromotion(${promotion.MaKM})">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn btn-sm btn-outline-danger" onclick="confirmDeletePromotion(${promotion.MaKM})">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `
    promotionTableBody.append(row)
  })
}

// Chuyển đổi trạng thái sang text
function getStatusText(status) {
  switch (Number.parseInt(status)) {
    case 1:
      return '<span class="badge bg-success">Đang hoạt động</span>'
    case 0:
      return '<span class="badge bg-secondary">Đã kết thúc</span>'
    case -1:
      return '<span class="badge bg-danger">Đã ẩn</span>'
    default:
      return '<span class="badge bg-warning">Không xác định</span>'
  }
}

// Kiểm tra lỗ vốn khi áp dụng khuyến mãi
function checkProductProfit(productId, discount) {
  if (!productId || !discount) return

  $.ajax({
    url: `../../admin/api/index.php?type=checkPromotionProfit&productId=${productId}&discount=${discount}`,
    method: "GET",
    dataType: "json",
    success: (response) => {
      if (response.success) {
        const data = response.data
        if (!data.valid) {
          // Đánh dấu sản phẩm có thể bị lỗ
          $(`#selectedProductsList tr[data-product-id="${productId}"]`).addClass("table-danger")

          // Hiển thị cảnh báo
          const warningHtml = `
            <div class="alert alert-warning mt-2" data-product="${productId}">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Sản phẩm <strong>${$(`#selectedProductsList tr[data-product-id="${productId}"] .product-name`).text()}</strong> 
              sẽ bị lỗ ${formatCurrency(data.loss_per_unit)}/sản phẩm nếu áp dụng khuyến mãi này.
            </div>
          `
          $("#profitWarnings").append(warningHtml)
        }
      }
    },
  })
}

// Gửi form thêm khuyến mãi
function submitPromotionForm() {
  // Lấy giá trị form
  const promotionName = $("#promotionName").val().trim()
  const promotionValue = $("#promotionValue").val()
  const startDate = $("#promotionStartDate").val()
  const endDate = $("#promotionEndDate").val()

  // Validate
  if (!promotionName) {
    showPromotionAlert("warning", "Vui lòng nhập tên khuyến mãi")
    $("#promotionName").focus()
    return
  }

  if (!promotionValue || isNaN(promotionValue) || promotionValue < 1 || promotionValue > 100) {
    showPromotionAlert("warning", "Giá trị khuyến mãi phải từ 1% đến 100%")
    $("#promotionValue").focus()
    return
  }

  if (!startDate) {
    showPromotionAlert("warning", "Vui lòng chọn ngày bắt đầu")
    $("#promotionStartDate").focus()
    return
  }

  if (!endDate) {
    showPromotionAlert("warning", "Vui lòng chọn ngày kết thúc")
    $("#promotionEndDate").focus()
    return
  }

  if (new Date(startDate) > new Date(endDate)) {
    showPromotionAlert("warning", "Ngày kết thúc phải sau ngày bắt đầu")
    return
  }

  // Kiểm tra danh sách sản phẩm
  const productIds = []
  $("#selectedProductsList tr").each(function () {
    productIds.push($(this).data("product-id"))
  })

  // if (productIds.length === 0) {
  //   showPromotionAlert("warning", "Vui lòng chọn ít nhất một sản phẩm")
  //   return
  // }

  // Kiểm tra nếu có sản phẩm nào bị lỗ vốn
  const invalidProducts = $("#selectedProductsList tr.table-danger").length
  if (invalidProducts > 0) {
    showPromotionAlert("warning", "Không thể áp dụng khuyến mãi do có sản phẩm bị lỗ vốn")
    return
  }
  $.ajax({
    url: "../../admin/api/index.php?type=addAndApplyPromotion",
    method: "POST",
    dataType: "json",
    data: {
      name: promotionName,
      value: promotionValue,
      startDate: startDate,
      endDate: endDate,
      products: productIds,
    },
    success: (response) => {
      if (response.success) {
        showPromotionAlert("success", response.message)
        setTimeout(() => {
          $("#addPromotionModal").modal("hide")
          loadPromotionList()
          resetPromotionForm()
        }, 1500)
      } else {
        showPromotionAlert("danger", response.message || "Lỗi khi thêm khuyến mãi")
      }
    },
    error: () => {
      showPromotionAlert("danger", "Lỗi kết nối đến server")
    },
  })
}
function resetPromotionForm() {
  $("#addPromotionForm")[0].reset()
  $("#selectedProductsList").empty()
  $("#promotionAlerts").empty()
  $("#profitWarnings").empty()
}

// Hiển thị thông báo trong modal thêm khuyến mãi
function showPromotionAlert(type, message) {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `
  $("#promotionAlerts").html(alertHtml)

  // Tự động ẩn thông báo sau 10 giây
  setTimeout(() => {
    $("#promotionAlerts .alert").alert("close")
  }, 10000)
}

// hàm show thông tin khi ấn nút sửa
function editPromotion(promotionId) {
  $("#editPromotionForm")[0].reset()
  $("#editSelectedProductsList").empty()
  $("#editPromotionAlerts").empty()
  $("#editProfitWarnings").empty()
  $.ajax({
    url: `../../admin/api/index.php?type=getPromotionDetail&id=${promotionId}`,
    method: "GET",
    dataType: "json",
    success: (response) => {
      if (response.success) {
        const promotion = response.promotion
        const products = response.products

        $("#editPromotionId").val(promotion.MaKM)
        $("#editPromotionName").val(promotion.TenKM)
        $("#editPromotionValue").val(promotion.giaTriKM)
        $("#editPromotionStartDate").val(formatDateForInput(promotion.NgayBatDau))
        $("#editPromotionEndDate").val(formatDateForInput(promotion.NgayKetThuc))
        $("#editPromotionStatus").val(promotion.TrangThai)
        const productList = $("#editSelectedProductsList")
        productList.empty()

        products.forEach((product) => {
          const template = document.getElementById("productRowTemplate").content.cloneNode(true)
          const row = template.querySelector("tr")
          row.setAttribute("data-product-id", product.MaSP)
          row.querySelector(".product-name").textContent = product.TenSP
          row.querySelector(".product-price").textContent = formatCurrency(product.GiaBan)

          // Thay đổi hàm onclick cho nút xóa
          row.querySelector("button").setAttribute("onclick", "removeProductFromEdit(this)")

          productList.append(template)
        })
        loadProductOptionsForEdit()

        $("#editPromotionModal").modal("show")
      } else {
        showAlert("danger", response.message || "Không thể tải thông tin khuyến mãi")
      }
    },
    error: () => {
      showAlert("danger", "Lỗi kết nối đến server")
    },
  })
}

// Tải danh sách sản phẩm cho dropdown sửa
function loadProductOptionsForEdit() {
  $.ajax({
    url: "../../admin/api/index.php?type=getAllProductsForPromotion",
    method: "GET",
    dataType: "json",
    success: (data) => {
      if (data.success) {
        const select = $("#editProductSelect")
        select.empty()
        select.append('<option value="">-- Chọn sản phẩm --</option>')

        data.data.forEach((product) => {
          // Chỉ hiển thị sản phẩm chưa có trong danh sách
          if ($(`#editSelectedProductsList tr[data-product-id="${product.MaSP}"]`).length === 0) {
            select.append(`<option value="${product.MaSP}" data-price="${product.GiaBan}">${product.TenSP}</option>`)
          }
        })
      }
    },
    error: () => {
      showAlert("danger", "Không thể tải danh sách sản phẩm")
    },
  })
}

// Thêm sản phẩm vào khuyến mãi đang sửa
function addProductToEditPromotion() {
  const select = $("#editProductSelect")
  const productId = select.val()
  const productName = select.find("option:selected").text()
  const productPrice = select.find("option:selected").data("price")

  if (!productId) {
    showEditPromotionAlert("warning", "Vui lòng chọn sản phẩm")
    return
  }

  if ($(`#editSelectedProductsList tr[data-product-id="${productId}"]`).length > 0) {
    showEditPromotionAlert("warning", "Sản phẩm đã có trong danh sách")
    return
  }

  const template = document.getElementById("productRowTemplate").content.cloneNode(true)
  const row = template.querySelector("tr")
  row.setAttribute("data-product-id", productId)
  row.querySelector(".product-name").textContent = productName
  row.querySelector(".product-price").textContent = formatCurrency(productPrice)

  // Thay đổi hàm onclick cho nút xóa
  row.querySelector("button").setAttribute("onclick", "removeProductFromEdit(this)")

  document.getElementById("editSelectedProductsList").appendChild(template)

  const discountValue = $("#editPromotionValue").val()
  if (discountValue) {
    checkEditProductProfit(productId, discountValue)
  }

  select.val("")
  loadProductOptionsForEdit()
}

// Xóa sản phẩm khỏi danh sách đã chọn trong modal sửa
function removeProductFromEdit(button) {
  const row = $(button).closest("tr")
  const productId = row.data("product-id")
  row.remove()
  $(`#editProfitWarnings .alert[data-product="${productId}"]`).remove()
  loadProductOptionsForEdit()
}

// Kiểm tra lỗ vốn khi áp dụng khuyến mãi trong modal sửa
function checkEditProductProfit(productId, discount) {
  if (!productId || !discount) return

  $.ajax({
    url: `../../admin/api/index.php?type=checkPromotionProfit&productId=${productId}&discount=${discount}`,
    method: "GET",
    dataType: "json",
    success: (response) => {
      if (response.success) {
        const data = response.data
        if (!data.valid) {
          // Đánh dấu sản phẩm có thể bị lỗ
          $(`#editSelectedProductsList tr[data-product-id="${productId}"]`).addClass("table-danger")

          // Hiển thị cảnh báo
          const warningHtml = `
            <div class="alert alert-warning mt-2" data-product="${productId}">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Sản phẩm <strong>${$(`#editSelectedProductsList tr[data-product-id="${productId}"] .product-name`).text()}</strong> 
              sẽ bị lỗ ${formatCurrency(data.loss_per_unit)}/sản phẩm nếu áp dụng khuyến mãi này.
            </div>
          `
          $("#editProfitWarnings").append(warningHtml)
        }
      }
    },
  })
}

// Cập nhật khuyến mãi
function updatePromotion() {
  const promotionId = $("#editPromotionId").val()
  const promotionData = {
    name: $("#editPromotionName").val().trim(),
    value: $("#editPromotionValue").val(),
    startDate: $("#editPromotionStartDate").val(),
    endDate: $("#editPromotionEndDate").val(),
    status: $("#editPromotionStatus").val(),
    products: [],
  }

  // Validate
  if (!promotionData.name) {
    showEditPromotionAlert("warning", "Vui lòng nhập tên khuyến mãi")
    $("#editPromotionName").focus()
    return
  }

  if (!promotionData.value || isNaN(promotionData.value) || promotionData.value < 1 || promotionData.value > 100) {
    showEditPromotionAlert("warning", "Giá trị khuyến mãi phải từ 1% đến 100%")
    $("#editPromotionValue").focus()
    return
  }

  if (!promotionData.startDate) {
    showEditPromotionAlert("warning", "Vui lòng chọn ngày bắt đầu")
    $("#editPromotionStartDate").focus()
    return
  }

  if (!promotionData.endDate) {
    showEditPromotionAlert("warning", "Vui lòng chọn ngày kết thúc")
    $("#editPromotionEndDate").focus()
    return
  }

  if (new Date(promotionData.startDate) > new Date(promotionData.endDate)) {
    showEditPromotionAlert("warning", "Ngày kết thúc phải sau ngày bắt đầu")
    return
  }
  $("#editSelectedProductsList tr").each(function () {
    promotionData.products.push($(this).data("product-id"))
  })

  // if (promotionData.products.length === 0) {
  //   showEditPromotionAlert("warning", "Vui lòng chọn ít nhất một sản phẩm")
  //   return
  // }

  // Kiểm tra nếu có sản phẩm nào bị lỗ vốn
  const invalidProducts = $("#editSelectedProductsList tr.table-danger").length
  if (invalidProducts > 0) {
    showEditPromotionAlert("warning", "Không thể áp dụng khuyến mãi do có sản phẩm bị lỗ vốn")
    return
  }
  $.ajax({
    url: "../../admin/api/index.php?type=updatePromotion",
    method: "POST",
    dataType: "json",
    data: {
      id: promotionId,
      ...promotionData,
    },
    success: (response) => {
      if (response.success) {
        console.log("Chi tiết cập nhật:", response.details);
        showEditPromotionAlert("success", response.message);
        
        // log
        if (response.details.removedProducts && response.details.removedProducts.length > 0) {
          console.group("Sản phẩm đã xóa khuyến mãi:");
          response.details.removedProducts.forEach(product => {
            console.log(`SP ${product.productId}: ${product.result.success ? 'Thành công' : 'Thất bại'}`);
          });
          console.groupEnd();
        }
        
        if (response.details.addedProducts && response.details.addedProducts.length > 0) {
          console.group("Sản phẩm đã thêm khuyến mãi:");
          response.details.addedProducts.forEach(product => {
            console.log(`SP ${product.productId}: ${product.status}`);
            if (product.details) {
              console.log("Chi tiết:", product.details);
            }
          });
          console.groupEnd();
        }
        
        if (response.statusChange) {
          console.log("Thay đổi trạng thái:", response.statusChange);
        }

        setTimeout(() => {
          $("#editPromotionModal").modal("hide");
          loadPromotionList();
        }, 1500);
      } else {
        console.error("Lỗi cập nhật:", response);
        showEditPromotionAlert("danger", response.message || "Lỗi khi cập nhật khuyến mãi");
      }
    },
    error: (xhr, status, error) => {
      console.error("Lỗi AJAX:", status, error);
      showEditPromotionAlert("danger", "Lỗi kết nối đến server");
    }
  });
}
function showEditPromotionAlert(type, message) {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `
  $("#editPromotionAlerts").html(alertHtml)

  // Tự động ẩn thông báo sau 10 giây
  setTimeout(() => {
    $("#editPromotionAlerts .alert").alert("close")
  }, 10000)
}

// Xác nhận xóa khuyến mãi
function confirmDeletePromotion(promotionId) {
  if (promotionId) {
    $("#editPromotionId").val(promotionId)
  }

  const id = $("#editPromotionId").val()

  // Hiển thị modal xác nhận
  $("#deleteMessage").text("Bạn có chắc chắn muốn xóa khuyến mãi này?")
  $("#confirmDeleteModal").modal("show")
}

// Xóa khuyến mãi
function deletePromotion() {
  const promotionId = $("#editPromotionId").val()

  $.ajax({
    url: "../../admin/api/index.php?type=deletePromotion",
    method: "POST",
    dataType: "json",
    data: { id: promotionId },
    success: (response) => {
      if (response.success) {
        showAlert("success", response.message)
        $("#confirmDeleteModal").modal("hide")
        $("#editPromotionModal").modal("hide")
        loadPromotionList()
      } else {
        showAlert("danger", response.message || "Lỗi khi xóa khuyến mãi")
        $("#confirmDeleteModal").modal("hide")
      }
    },
    error: () => {
      showAlert("danger", "Lỗi kết nối đến server")
      $("#confirmDeleteModal").modal("hide")
    },
  })
}

// Hiển thị thông báo chung
function showAlert(type, message) {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `
  if ($("#globalAlerts").length === 0) {
    $("body").prepend(
      '<div id="globalAlerts" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 350px;"></div>',
    )
  }

  const $alert = $(alertHtml).appendTo("#globalAlerts")

  // Tự động ẩn sau 5 giây
  setTimeout(() => {
    $alert.alert("close")
  }, 5000)
}

// Định dạng ngày
function formatDate(dateString) {
  if (!dateString) return ""
  const date = new Date(dateString)
  return date.toLocaleDateString("vi-VN")
}
// Định dạng ngày cho input = date
function formatDateForInput(dateString) {
  return new Date(dateString).toISOString().split("T")[0]
}
// Định dạng tiền tệ
function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(amount)
}

// Tìm kiếm sản phẩm
function searchProduct() {
  const searchTerm = $('.toolbar input[type="text"]').val().toLowerCase()

  if (!searchTerm) {
    loadPromotionList()
    return
  }

  $("#promotionTableBody tr").each(function () {
    const text = $(this).text().toLowerCase()
    $(this).toggle(text.indexOf(searchTerm) > -1)
  })

  if ($("#promotionTableBody tr:visible").length === 0) {
    $("#promotionTableBody").html(
      '<tr><td colspan="7" class="text-center py-4">Không tìm thấy kết quả phù hợp!</td></tr>',
    )
  }
}

handlePromotions()
