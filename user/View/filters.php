<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Lọc sản phẩm</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <!-- Danh mục sản phẩm -->
            <div class="mb-3">
                <h6 class="fw-bold">Danh mục</h6>
                <div id="category-filters">
                    <!-- Danh mục sẽ được load bằng JavaScript -->
                </div>
            </div>

            <!-- Phân loại (sẽ hiển thị khi chọn danh mục) -->
            <div class="mb-3" id="type-filters-container" style="display: none;">
                <h6 class="fw-bold">Phân loại</h6>
                <div id="type-filters">
                    <!-- Phân loại sẽ được load động -->
                </div>
            </div>
            <!-- Giá (radio: chỉ chọn 1) -->
            <div class="mb-3">
                <h6 class="fw-bold">Giá</h6>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="radio" name="price" id="price-all" value="" checked>
                    <label class="form-check-label" for="price-all">Tất cả giá</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="radio" name="price" id="price-under100" value="under100">
                    <label class="form-check-label" for="price-under100">Dưới 100.000</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="radio" name="price" id="price-100-500" value="100-500">
                    <label class="form-check-label" for="price-100-500">Từ 100.000-500.000</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="radio" name="price" id="price-500-1000" value="500-1000">
                    <label class="form-check-label" for="price-500-1000">Từ 500.000-1.000.000</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="radio" name="price" id="price-over1000" value="over1000">
                    <label class="form-check-label" for="price-over1000">Trên 1.000.000</label>
                </div>
            </div>

            <!-- Giới tính -->
            <div class="mb-3">
                <h6 class="fw-bold">Giới tính</h6>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="gender" id="gender-female" value="female">
                    <label class="form-check-label" for="gender-female">Nữ</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="gender" id="gender-male" value="male">
                    <label class="form-check-label" for="gender-male">Nam</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="gender" id="gender-unisex" value="unisex">
                    <label class="form-check-label" for="gender-unisex">Unisex</label>
                </div>
            </div>

            <!-- Size -->
            <div class="mb-3">
                <h6 class="fw-bold">Size</h6>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="size" id="size-s" value="s">
                    <label class="form-check-label" for="size-s">S</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="size" id="size-m" value="m">
                    <label class="form-check-label" for="size-m">M</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="size" id="size-l" value="l">
                    <label class="form-check-label" for="size-l">L</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="size" id="size-xl" value="xl">
                    <label class="form-check-label" for="size-xl">XL</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input filter-option" type="checkbox" name="size" id="size-xxl" value="xxl">
                    <label class="form-check-label" for="size-xxl">XXL</label>
                </div>
            </div>
        </form>
    </div>
</div>