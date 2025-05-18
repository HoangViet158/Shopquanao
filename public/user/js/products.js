$(document).ready(() => {
  loadCategories()
  loadCategoryFilters()
  const params = new URLSearchParams(window.location.search)
  if (params.toString() === "") {
    loadProductData()
  } else {
    filterProductData()
  }

  // Sự kiện khi chọn danh mục trong filter
  $(document).on("change", 'input[name="category-filter"]', function() {
  const categoryId = $(this).val();
  
  // Xóa tất cả các checkbox type đã chọn trước
  $('input[name="type-filter"]').prop("checked", false);
  
  if ($(this).is(":checked")) {
    // Chỉ load nếu chưa có
    if ($(`.type-filter-group[data-category="${categoryId}"]`).length === 0) {
      loadTypeFilters(categoryId);
    }
    $("#type-filters-container").show();
  } else {
    // Xóa nhóm phân loại tương ứng
    $(`.type-filter-group[data-category="${categoryId}"]`).remove();
    
    // Ẩn phần phân loại nếu không còn danh mục nào được chọn
    if ($('input[name="category-filter"]:checked').length === 0) {
      $("#type-filters-container").hide();
    }
  }
  
  updateURLFromFilters();
  filterProductData();
});

  // Sự kiện hover cho dropdown menu
  $(document).on("mouseenter", ".dropdown-submenu", function () {
    const $this = $(this)
    const categoryId = $this.find("> a").data("category")

    if (categoryId !== "all" && !$this.hasClass("loaded")) {
      loadTypesForCategory(categoryId, $this)
      $this.addClass("loaded")
    }

    $this.addClass("show")
    $this.find("> .dropdown-menu").addClass("show")
  })

  $(document).on("mouseleave", ".dropdown-submenu", function () {
    const $this = $(this)
    $this.removeClass("show")
    $this.find("> .dropdown-menu").removeClass("show")
  })

  // Sự kiện khi click vào danh mục
  $(document).on("click", ".dropdown-item[data-category]", function (e) {
    e.preventDefault()
     $("#type-filters").empty(); 
  $("#type-filters-container").hide(); 
    const categoryId = $(this).data("category")
    const currentPath = window.location.pathname
    const basePath = currentPath.substring(0, currentPath.lastIndexOf("/")) + "/product.php"

    let targetUrl = basePath
    $('input[name="category-filter"]').prop("checked", false)

    if (categoryId !== "all") {
      const params = new URLSearchParams()
      params.set("categories", categoryId)
      //  loadTypeFilters(categoryId);
      //  params.delete("types"); 
      
      targetUrl += "?" + params.toString()
      $(`input[name="category-filter"][value="${categoryId}"]`).prop("checked", true)
      
    }

    if (window.location.pathname.includes("product.php")) {
      window.history.pushState({}, "", targetUrl)
      filterProductData()
    } else {
      window.location.href = targetUrl
    }
  })

  // Sự kiện khi click vào phân loại trong dropdown
  $(document).on("click", ".dropdown-item[data-type]", function (e) {
    e.preventDefault()
    e.stopPropagation()
    const typeId = $(this).data("type")
    const categoryId = $(this).closest(".types-menu").data("category")
    const params = new URLSearchParams(window.location.search)
    params.set("types", typeId)
    params.set("categories", categoryId)

    if (window.location.pathname.includes("product.php")) {
      window.history.pushState({}, "", `${location.pathname}?${params.toString()}`)
      filterProductData()
    } else {
      window.location.href = `../View/product.php?${params.toString()}`
    }
  })
  $(document).on("change", 'input[name="type-filter"]', () => {
    updateURLFromFilters()
    filterProductData()
  })

  // Sự kiện khi thay đổi các filter khác
  $(".filter-option").change(() => {
    updateURLFromFilters()
    filterProductData()
  })

  // Sự kiện tìm kiếm theo từ khóa
  $(".findByKeyword").on("click", () => {
    const params = new URLSearchParams(window.location.search)
    const keyword = $(".nameTxt").val().trim()
    const currentPath = window.location.pathname

    if (keyword && !currentPath.includes("product.php")) {
      params.set("keyword", keyword)
      window.location.href = `../View/product.php?${params.toString()}`
    } else if (keyword) {
      params.set("keyword", keyword)
    } else {
      params.delete("keyword")
    }

    window.history.pushState({}, "", `${location.pathname}?${params.toString()}`)
    filterProductData()
  })

  // Sự kiện phân trang
  $(document).on("click", ".page-link", function (e) {
    e.preventDefault()
    const page = $(this).attr("href").split("page=")[1]
    updateURLParameter("page", page)
    const params = new URLSearchParams(window.location.search)
    if (params.toString() === "" || params.toString() === "page=" + page) {
      loadProductData(page)
    } else {
      filterProductData(page)
    }
  })
})

// ========== CÁC HÀM CHỨC NĂNG ========== //

function loadCategories() {
  $.ajax({
    url: "../../admin/API/index.php?type=getAllCategories",
    type: "GET",
    dataType: "json",
    success: (data) => {
      const menu = $("#categoriesMenu")
      menu.empty()
      menu.append('<li><a class="dropdown-item" href="#" data-category="all">Tất cả sản phẩm</a></li>')

      data.forEach((category) => {
        menu.append(`
                    <li class="dropdown-submenu">
                        <a class="dropdown-item" href="#" data-category="${category.MaDM}">
                            ${category.TenDM}
                        </a>
                        <ul class="dropdown-menu types-menu" data-category="${category.MaDM}">
                            <li><a class="dropdown-item disabled" href="#">Đang tải...</a></li>
                        </ul>
                    </li>
                `)
      })
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải danh mục:", error)
    },
  })
}

function loadCategoryFilters() {
  $.ajax({
    url: "../../admin/API/index.php?type=getAllCategories",
    type: "GET",
    dataType: "json",
    success: (data) => {
      const container = $("#category-filters")
      container.empty()

      data.forEach((category) => {
        container.append(`
                    <div class="form-check">
                        <input class="form-check-input filter-option" type="checkbox" 
                               name="category-filter" id="category-${category.MaDM}" 
                               value="${category.MaDM}">
                        <label class="form-check-label" for="category-${category.MaDM}">
                            ${category.TenDM}
                        </label>
                    </div>
                `)
      })

      // Cập nhật trạng thái từ URL
      updateActiveFilters()
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải danh mục:", error)
    },
  })
}

function loadTypeFilters(categoryId) {
  // Kiểm tra xem đã load nhóm phân loại cho danh mục này chưa
  if ($(`.type-filter-group[data-category="${categoryId}"]`).length > 0) {
    return
  }

  $.ajax({
    url: `../../admin/API/index.php?type=getAllTypeByCategory&id=${categoryId}`,
    type: "GET",
    dataType: "json",
    success: (types) => {
      const container = $("#type-filters")
      if ($(`.type-filter-group[data-category="${categoryId}"]`).length > 0) {
        return;
      }
      // Tạo nhóm phân loại với class để nhận biết đã load
      const group = $(`
        <div class="type-filter-group mb-3 loaded" data-category="${categoryId}">
          <div class="mb-2">
            <small class="text-muted">${$(`label[for="category-${categoryId}"]`).text()}:</small>
          </div>
        </div>
      `);

      types.forEach((type) => {
        group.append(`
          <div class="form-check">
            <input class="form-check-input filter-option" type="checkbox" 
                   name="type-filter" id="type-${type.MaPL}" 
                   value="${type.MaPL}">
            <label class="form-check-label" for="type-${type.MaPL}">
              ${type.TenPL}
            </label>
          </div>
        `);
      });

      container.append(group);
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải phân loại:", error)
    },
  })
}

function loadTypesForCategory(categoryId, element) {
  $.ajax({
    url: `../../admin/API/index.php?type=getAllTypeByCategory&id=${categoryId}`,
    type: "GET",
    dataType: "json",
    success: (types) => {
      const typesMenu = element.find(".types-menu")
      typesMenu.empty()

      if (types.length > 0) {
        types.forEach((type) => {
          typesMenu.append(`
                        <li>
                            <a class="dropdown-item" href="#" data-type="${type.MaPL}">
                                ${type.TenPL}
                            </a>
                        </li>
                    `)
        })
      } else {
        typesMenu.append('<li><a class="dropdown-item disabled" href="#">Không có phân loại</a></li>')
      }
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải phân loại:", error)
    },
  })
}

function updateURLFromFilters() {
  const params = new URLSearchParams(window.location.search)

  // Danh mục
  const categories = []
  $('input[name="category-filter"]:checked').each(function () {
    categories.push($(this).val())
  })
  if (categories.length) params.set("categories", categories.join(","))
  else params.delete("categories")

  // Phân loại
  const types = []
  $('input[name="type-filter"]:checked').each(function () {
    types.push($(this).val())
  })
  if (types.length) params.set("types", types.join(","))
  else params.delete("types")

  // Giá
  const price = $('input[name="price"]:checked').val()
  if (price) params.set("price", price)
  else params.delete("price")

  // Giới tính
  const genders = []
  $('input[name="gender"]:checked').each(function () {
    genders.push($(this).val())
  })
  if (genders.length) params.set("genders", genders.join(","))
  else params.delete("genders")

  // Size
  const sizes = []
  $('input[name="size"]:checked').each(function () {
    sizes.push($(this).val())
  })
  if (sizes.length) params.set("sizes", sizes.join(","))
  else params.delete("sizes")

  // Keyword
  const keyword = $(".nameTxt").val().trim()
  if (keyword) params.set("keyword", keyword)
  else params.delete("keyword")

  // Cập nhật URL
  window.history.pushState({}, "", `${location.pathname}?${params.toString()}`)
}

function updateActiveFilters() {
  const params = new URLSearchParams(window.location.search)

  // Danh mục
  const categories = params.get("categories") ? params.get("categories").split(",") : [];
  $('input[name="category-filter"]').prop("checked", false);
  
  categories.forEach((cat) => {
    $(`input[name="category-filter"][value="${cat}"]`).prop("checked", true);
    
    // Chỉ load nếu chưa có
    if ($(`.type-filter-group[data-category="${cat}"]`).length === 0) {
      loadTypeFilters(cat);
    }
  });

  $("#type-filters-container").toggle(categories.length > 0);

  // Phân loại
  const types = params.get("types") ? params.get("types").split(",") : []
  $('input[name="type-filter"]').prop("checked", false)
  types.forEach((type) => {
    $(`input[name="type-filter"][value="${type}"]`).prop("checked", true)
  })

  // Giá
  const price = params.get("price")
  $('input[name="price"]').prop("checked", false)
  if (price) $(`input[name="price"][value="${price}"]`).prop("checked", true)
  else $("#price-all").prop("checked", true)

  // Giới tính
  const genders = params.get("genders") ? params.get("genders").split(",") : []
  $('input[name="gender"]').prop("checked", false)
  genders.forEach((gender) => {
    $(`input[name="gender"][value="${gender}"]`).prop("checked", true)
  })

  // Size
  const sizes = params.get("sizes") ? params.get("sizes").split(",") : []
  $('input[name="size"]').prop("checked", false)
  sizes.forEach((size) => {
    $(`input[name="size"][value="${size}"]`).prop("checked", true)
  })

  // Keyword
  const keyword = params.get("keyword")
  if (keyword) $(".nameTxt").val(keyword)
}

function filterProductData(page = 1) {
  const params = new URLSearchParams(window.location.search)
  if (params.toString() === "") {
    loadProductData(page)
    return
  }

  let apiUrl = `../../user/API/index.php?type=filter&page=${page}`

  // Thêm các tham số filter
  if (params.get("categories")) apiUrl += `&categories=${params.get("categories")}`
  if (params.get("types")) apiUrl += `&types=${params.get("types")}`
  if (params.get("price")) apiUrl += `&price=${params.get("price")}`
  if (params.get("genders")) apiUrl += `&genders=${params.get("genders")}`
  if (params.get("sizes")) apiUrl += `&sizes=${params.get("sizes")}`
  if (params.get("keyword")) apiUrl += `&keyword=${params.get("keyword")}`

  $.ajax({
    url: apiUrl,
    type: "GET",
    dataType: "json",
    success: (data) => {
      $("#product-list").html(renderProducts(data.products))
      $("#pagination").html(renderPagination(data.pagination))
      updateActiveFilters()
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải dữ liệu:", error)
    },
  })
}

function loadProductData(page = 1) {
  $.ajax({
    url: `../../admin/API/index.php?type=getAllProducts&page=${page}`,
    type: "GET",
    dataType: "json",
    success: (data) => {
      $("#product-list").html(renderProducts(data.products))
      $("#pagination").html(renderPagination(data.pagination))
    },
    error: (xhr, status, error) => {
      console.error("Lỗi khi tải dữ liệu:", error)
    },
  })
}

function renderProducts(products) {
  if (products.length === 0) {
    return '<div class="alert alert-info">Không có sản phẩm nào phù hợp</div>'
  }

  let html = '<div class="row">'

  products.forEach((product) => {
    const imageSrc = Array.isArray(product.Anh) ? product.Anh[0] : product.Anh
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
                    </div>
                </div>
            </div>
        </div>
        `
  })

  html += "</div>"
  return html
}

function renderPagination(pagination) {
  if (pagination.total_pages <= 1) {
    $("#pagination").hide()
    return ""
  }

  $("#pagination").show()
  let html = `
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
    `

  if (pagination.current_page > 1) {
    html += `
        <li class="page-item">
            <a class="page-link" href="?page=${pagination.current_page - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        `
  }

  for (let i = 1; i <= pagination.total_pages; i++) {
    html += `
        <li class="page-item ${i == pagination.current_page ? "active" : ""}">
            <a class="page-link" href="?page=${i}">${i}</a>
        </li>
        `
  }

  if (pagination.current_page < pagination.total_pages) {
    html += `
        <li class="page-item">
            <a class="page-link" href="?page=${pagination.current_page + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        `
  }

  html += `
        </ul>
    </nav>
    `
  return html
}

function formatPrice(price) {
  return Number.parseInt(price).toLocaleString("vi-VN")
}

function updateURLParameter(key, value) {
  const params = new URLSearchParams(window.location.search)
  params.set(key, value)
  window.history.pushState({}, "", `${location.pathname}?${params.toString()}`)
}
