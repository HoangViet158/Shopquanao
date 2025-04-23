function handleBill() {
    const Mange_client = document.getElementsByClassName('Mange_client')[0];
    const Bill = `
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">Quản lý đơn hàng</h5>
                <div class="filter-section">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter" onchange="filterBills()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="0">Chưa xác nhận</option>
                                <option value="1">Đã xác nhận</option>
                                <option value="2">Đã giao thành công</option>
                                <option value="3">Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="fromDate" onchange="filterBills()">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="toDate" onchange="filterBills()">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="locationFilter" onchange="filterBills()">
                                <option value="">Tất cả địa điểm</option>
                                <!-- Các option quận/huyện sẽ được thêm bằng JS -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="billTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mã HD</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Địa chỉ giao</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="billList">
                            <!-- Danh sách hóa đơn sẽ được load ở đây -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chi tiết hóa đơn -->
    <div class="modal fade" id="billDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết hóa đơn #<span id="billIdHeader"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Khách hàng:</strong> <span id="customerName"></span></p>
                            <p><strong>Địa chỉ:</strong> <span id="customerAddress"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày đặt:</strong> <span id="orderDate"></span></p>
                            <p><strong>Trạng thái:</strong> <span id="orderStatus"></span></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Size</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="billDetailList">
                                <!-- Chi tiết hóa đơn sẽ được load ở đây -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><span id="billTotal"></span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="status-control mt-3">
                        <label for="statusChange"><strong>Cập nhật trạng thái:</strong></label>
                        <select class="form-select" id="statusChange">
                            <option value="0">Chưa xác nhận</option>
                            <option value="1">Đã xác nhận</option>
                            <option value="2">Đã giao thành công</option>
                            <option value="3">Đã hủy</option>
                        </select>
                        <button class="btn btn-primary mt-2" onclick="updateBillStatus()">Cập nhật</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    `;
    Mange_client.innerHTML = Bill;
    loadBillList();
    loadLocationOptions();
}

// Hàm xử lý routing
function handleRouting() {
    const path = window.location.pathname;
    const searchParams = new URLSearchParams(window.location.search);
    
    // Xử lý khi có query params (filter)
    if (path.includes('/bills') && searchParams.toString()) {
        const status = searchParams.get('status');
        const fromDate = searchParams.get('from');
        const toDate = searchParams.get('to');
        const location = searchParams.get('location');
        
        if (status) $('#statusFilter').val(status);
        if (fromDate) $('#fromDate').val(fromDate);
        if (toDate) $('#toDate').val(toDate);
        if (location) $('#locationFilter').val(location);
        
        filterBills();
    }
    // Xử lý khi xem chi tiết hóa đơn
    else if (path.includes('/bills/')) {
        const billId = path.split('/bills/')[1];
        showBillDetail(billId);
    }
    // Mặc định load danh sách
    else {
        loadBillList();
    }
}

// Thêm sự kiện popstate để xử lý khi người dùng nhấn back/forward
window.addEventListener('popstate', function(event) {
    handleRouting();
});

function loadBillList() {
    $.ajax({
        url: '../../admin/api/index.php?type=getAllBill',
        method: 'GET',
        dataType: 'json',
        success: (data) => {
            renderBillList(data);
            // Cập nhật URL khi load danh sách
            history.pushState({}, '', '/bills');
        },
        error: (error) => {
            console.error("Lỗi khi tải danh sách hóa đơn: " + error);
        }
    });
}

function renderBillList(data) {
    const billTable = $("#billList");
    billTable.empty();
    
    if (data.length === 0) {
        billTable.append('<tr><td colspan="7" class="text-center py-4">Không có hóa đơn nào</td></tr>');
        return;
    }
    
    data.forEach(bill => {
        const statusText = getStatusText(bill.TrangThai);
        const statusClass = getStatusClass(bill.TrangThai);
        
        const row = `
        <tr>
            <td>${bill.MaHD}</td>
            <td>${bill.taikhoan.TenTK}</td>
            <td>${formatDate(bill.ThoiGian)}</td>
            <td>${formatCurrency(bill.ThanhToan)}</td>
            <td><span class="badge ${statusClass}">${statusText}</span></td>
            <td>${bill.taikhoan.DiaChi}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="showBillDetail(${bill.MaHD})">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </button>
            </td>
        </tr>
        `;
        billTable.append(row);
    });
}

function showBillDetail(billId) {
    if (window.router) {
        router.navigate(`/bills/${billId}`);
    }
    $.ajax({
        url: `../../admin/api/index.php?type=getAllBillDetail&MaHD=${billId}`,
        method: 'GET',
        dataType: 'json',
        success: (detailData) => {
            // Lấy thông tin chung của hóa đơn
            $.ajax({
                url: `../../admin/api/index.php?type=getAllBill`,
                method: 'GET',
                dataType: 'json',
                success: (billData) => {
                    const bill = billData.find(b => b.MaHD == billId);
                    if (bill) {
                        renderBillDetail(bill, detailData);
                        $('#billDetailModal').modal('show');
                    }
                }
            });
        },
        error: (error) => {
            console.error("Lỗi khi tải chi tiết hóa đơn: " + error);
        }
    });
}

function renderBillDetail(bill, details) {
    $('#billIdHeader').text(bill.MaHD);
    // $('#customerName').text(bill.khachhang.TenKH);
    // $('#customerAddress').text(bill.DiaChiGiao);
    // $('#orderDate').text(formatDate(bill.NgayDat));
    // $('#orderStatus').text(getStatusText(bill.TrangThai));
    $('#statusChange').val(bill.TrangThai);
    
    const detailTable = $("#billDetailList");
    detailTable.empty();
    
    let total = 0;
    details.forEach(detail => {
        const subtotal = detail.SoLuong * detail.DonGia;
        total += subtotal;
        
        const row = `
        <tr>
            <td>${detail.TenSP}</td>
            <td>${detail.TenSize}</td>
            <td>${detail.SoLuongBan}</td>
            <td>${formatCurrency(detail.DonGia)}</td>
            <td>${formatCurrency(detail.ThanhTien)}</td>
        </tr>
        `;
        detailTable.append(row);
    });
    
    // $('#billTotal').text(formatCurrency(total));
}


function updateBillStatus() {
    const billId = $('#billIdHeader').text();
    const newStatus = $('#statusChange').val();
    
    // Kiểm tra trạng thái hiện tại
    const currentStatus = parseInt($('#statusChange').val());
    
    // Kiểm tra cập nhật ngược
    if (newStatus < currentStatus) {
        alert('Không thể cập nhật ngược trạng thái đơn hàng!');
        return;
    }
    
    $.ajax({
        url: '../../admin/api/index.php?type=updateBillStatus',
        method: 'POST',
        data: {
            MaHD: billId,
            TrangThai: newStatus
        },
        success: (response) => {
            alert('Cập nhật trạng thái thành công!');
            $('#billDetailModal').modal('hide');
            loadBillList();
            // Quay lại URL danh sách sau khi cập nhật
            history.pushState({}, '', '/bills');
        },
        error: (error) => {
            console.error("Lỗi khi cập nhật trạng thái: " + error);
            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
        }
    });
}

function filterBills() {
    const status = $('#statusFilter').val();
    const fromDate = $('#fromDate').val();
    const toDate = $('#toDate').val();
    const location = $('#locationFilter').val();
    
    // Tạo query params
    const queryParams = new URLSearchParams();
    if (status) queryParams.set('status', status);
    if (fromDate) queryParams.set('from', fromDate);
    if (toDate) queryParams.set('to', toDate);
    if (location) queryParams.set('location', location);
    
    // Cập nhật URL với query params
    history.pushState({ filter: true }, '', `/bills?${queryParams.toString()}`);
    
    $.ajax({
        url: '../../admin/api/index.php?type=getAllBill',
        method: 'GET',
        dataType: 'json',
        success: (data) => {
            let filteredData = data;
            
            if (status !== "") {
                filteredData = filteredData.filter(bill => bill.TrangThai == status);
            }
            
            if (fromDate) {
                const from = new Date(fromDate);
                filteredData = filteredData.filter(bill => {
                    const billDate = new Date(bill.ThoiGian);
                    return billDate >= from;
                });
            }
            
            if (toDate) {
                const to = new Date(toDate);
                filteredData = filteredData.filter(bill => {
                    const billDate = new Date(bill.ThoiGian);
                    return billDate <= to;
                });
            }
            
            if (location !== "") {
                filteredData = filteredData.filter(bill => {
                    return bill.taikhoan.DiaChi.includes(location);
                });
            }
            
            renderBillList(filteredData);
        },
        error: (error) => {
            console.error("Lỗi khi lọc hóa đơn: " + error);
        }
    });
}

function loadLocationOptions() {
    const districts = ["Quận 1", "Quận 2", "Quận 3", "Quận 4", "Quận 5", "Quận 6", "Quận 7", "Quận 8", 
                      "Quận 9", "Quận 10", "Quận 11", "Quận 12", "Tân Bình", "Bình Thạnh", "Gò Vấp", "Phú Nhuận"];
    
    const select = $('#locationFilter');
    districts.forEach(district => {
        select.append(`<option value="${district}">${district}</option>`);
    });
}

// Các hàm hỗ trợ
function getStatusText(status) {
    switch (parseInt(status)) {
        case 0: return "Chưa xác nhận";
        case 1: return "Đã xác nhận";
        case 2: return "Đã giao thành công";
        case 3: return "Đã hủy";
        default: return "Không xác định";
    }
}

function getStatusClass(status) {
    switch (parseInt(status)) {
        case 0: return "bg-secondary";
        case 1: return "bg-primary";
        case 2: return "bg-success";
        case 3: return "bg-danger";
        default: return "bg-warning";
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}
document.addEventListener('DOMContentLoaded', () => {
    // Đăng ký hàm xử lý
    if (typeof router !== 'undefined') {
        router.registerHandler('handleBill', handleBill);
        
        // Hoặc đăng ký trực tiếp route nếu cần custom
        router.addRoute('/bills/:id', null, (params) => {
            handleBill();
            showBillDetail(params.id);
        });
    }
});