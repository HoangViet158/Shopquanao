<?php
require_once "sidebar.php" ?>
<div id="statistic-content" class="content-container" style="max-width:80%">
    <div class="Mange_client" >
        <h3> Quản lý quyền </h3>
        <div class="toolbar mb-3 d-flex justify-content-between align-items-center">
            <div class="input-group" style="width:300px;">
                <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm...">
                <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;" type="button" onClick="searchPermission()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <button class="btn" style="background-color: #89CFF0; border-color: #89CFF0; color: black;" onclick="openAddPermissionModal()">
                <i class="fas fa-plus"></i>
                <span>Thêm mới</span>
            </button>
        </div>

        <div class="table-responsive">
            <table id="table-permission" class="table table-custom table-striped table-hover"></table>
        </div>

        <!-- Phân trang -->
        <div id="permission-pagination" class="mt-3"></div>
    </div>
<!-- Modal Thêm Quyền -->
<div id="addPermissionModal" class="modal" tabindex="-1" style="display:none; background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            
            <!-- Header -->
            <div class="modal-header position-relative justify-content-center border-0">
                <h5 class="modal-title text-center w-100">Thêm quyền mới</h5>
                <!-- Close button -->
                <button type="button"
                        class="btn-close position-absolute top-0 end-0 m-2"
                        id="addPermissionClose"
                        aria-label="Close"></button>
            </div>
            
            <!-- Body -->
            <div class="modal-body">
                <form id="addPermissionForm">
                <div class="row g-3">
                    <div>
                    <label class="form-label">Tên quyền</label>
                    <input type="text" name="tenQuyen" class="form-control" required>
                    </div>
                </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="modal-footer border-0 justify-content-end">
                <button type="submit" form="addPermissionForm" class="btn btn-primary">
                Thêm
                </button>
            </div>
            
            </div>
        </div>
    </div>
    <!-- Edit Permission Modal -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" >
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPermissionForm">
                    <div class="modal-header position-relative justify-content-center border-0">
                    <h5 class="modal-title text-center w-100" id="editPermissionModalLabel">Chỉnh sửa chi tiết quyền</h5>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                    <input type="hidden" id="editPermissionId" name="MaNguoiDung">

                    <div class="mb-3 col-sm-6">
                        <label for="editTenTK" class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" id="editTenTK" name="TenTK" required>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="editMatKhau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="editMatKhau" name="MatKhau">
                        <small class="form-text text-muted">Để trống nếu không đổi mật khẩu</small>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="Email" required>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="editDiaChi" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="editDiaChi" name="DiaChi">
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="editMaLoai" class="form-label">Mã loại</label>
                        <select class="form-select" id="editMaLoai" name="MaLoai">
                            <!-- <option value="1">Khách hàng</option>
                            <option value="2">Nhân viên</option>
                            <option value="3">Quản trị viên</option> -->
                        </select>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <label for="editMaQuyen" class="form-label">Mã quyền</label>
                        <select class="form-select" id="editMaQuyen" name="MaQuyen">
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" form="editPermissionForm" class="btn btn-primary" onclick="updatePermission()">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
<script src="../../public/admin/js/permission.js"></script>
</html>
