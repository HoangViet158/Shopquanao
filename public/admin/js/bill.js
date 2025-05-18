
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
                            <input type="text" class="form-control" id="addressFilter" placeholder="Nhập địa chỉ">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="filterBills()">Lọc</button>
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
    <!-- Modal cập nhật trạng thái -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái hóa đơn #<span id="updateBillId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="statusAlert" class="alert alert-warning d-none">
                    Không thể cập nhật hóa đơn đã kết thúc
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái hiện tại: <span id="currentStatus"></span></label>
                </div>
                <div class="mb-3">
                    <label for="newStatus" class="form-label">Chọn trạng thái mới:</label>
                    <select class="form-select" id="newStatus">
                        <option value="0">Chưa xác nhận</option>
                        <option value="1">Đã xác nhận</option>
                        <option value="2">Đã giao thành công</option>
                        <option value="3">Đã hủy</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateBtn" onclick="confirmUpdateStatus()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
    `;
    Mange_client.innerHTML = Bill;
    loadBillList();
    // $("#billDetailModal").on('hidden.bs.modal', ()=>{
    //     router.navigate('/bills',{},true)
    // })
    // $("#updateStatusModal").on('hidden.bs.modal', ()=>{
    //     router.navigate('/bills',{},true)
    // })
}
function filterBills() {
    const status = $('#statusFilter').val();
    const fromDate = $('#fromDate').val();
    const toDate = $('#toDate').val();
    const address = $('#addressFilter').val().trim();

    // Validate
    if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Ngày bắt đầu không thể lớn hơn ngày kết thúc',
            confirmButtonText: 'Đã hiểu'
        });
        return;
    }
    
    // Tạo object filter
    const filters = {};
    if (status !== '') filters.status = status;
    if (fromDate) filters.fromDate = fromDate;
    if (toDate) filters.toDate = toDate;
    if (address) filters.address = address;
    $.ajax({
        url: '../../admin/API/index.php?type=filterBills',
        method: 'POST',  
        data: JSON.stringify(filters),
        contentType: 'application/json',
        dataType: 'json',
        success: (data) => {
            if (data && data.length > 0) {
                renderBillList(data);
                // Update URL
                // if (window.router) {
                //     const queryString = new URLSearchParams(filters).toString();
                //     router.navigate(`/bills?${queryString}`, {}, true);
                // }
            } else {
                $('#billList').html('<tr><td colspan="7" class="text-center py-4">Không tìm thấy hóa đơn nào phù hợp</td></tr>');
            }
        },
        error: (error) => {
            console.error('Lỗi khi lọc hóa đơn:', error);
            alert('Lỗi khi lọc hóa đơn');
        }
    });
}
function loadBillList() {
    // Lấy các tham số từ URL nếu có
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const fromDate = urlParams.get('fromDate');
    const toDate = urlParams.get('toDate');
    const address = urlParams.get('address');

    if (status || fromDate || toDate || address) {
        // Đặt giá trị cho các filter
        if (status) $('#statusFilter').val(status);
        if (fromDate) $('#fromDate').val(fromDate);
        if (toDate) $('#toDate').val(toDate);
        if (address) $('#addressFilter').val(address);
    
        filterBills();
    } else {

        $.ajax({
            url: '../../admin/API/index.php?type=getAllBill',
            method: 'GET',
            dataType: 'json',
            success: (data) => {
                renderBillList(data);
            },
            error: (error) => {
                console.error("Lỗi khi tải danh sách hóa đơn: " + error);
            }
        });
    }
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
                <button class="btn btn-sm btn-outline-success me-2" onclick="updateBillStatus(${bill.MaHD})">
                      <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="showBillDetail(${bill.MaHD})">
                      <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
        `;
        billTable.append(row);
    });
}
function updateBillStatus(billId) {
    console.log(actionPermissions.canEdit)
    if(!actionPermissions.canEdit){
           Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không có quyền hạn truy cập chức năng này!',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
    }
    // if (window.router) {
    //     router.navigate(`/bills/updateStatus/${billId}`);
    // }
    
    // Hiển thị modal cập nhật trạng thái
    $('#updateBillId').text(billId);
    
    // Lấy thông tin hóa đơn hiện tại
    $.ajax({
        url: `../../admin/API/index.php?type=getAllBillDetail&MaHD=${billId}`,
        method: 'GET',
        dataType: 'json',
        success: (bill) => {
            if ([2, 3].includes(parseInt(bill[0].TrangThai))) {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể cập nhật!',
                    text: 'Hóa đơn đã kết thúc, không thể cập nhật trạng thái!',
                    confirmButtonText: 'Đã hiểu'
                });
                return;
            }
            console.log(bill[0].TrangThai)
            $('#currentStatus').text(getStatusText(bill[0].TrangThai));
            $('#newStatus').val(bill[0].TrangThai);
            $('#updateStatusModal').modal('show');
        },
        error: (error) => {
            console.error('Lỗi khi lấy thông tin hóa đơn:', error);
            alert('Không thể lấy thông tin hóa đơn');
        }
    });
}

function confirmUpdateStatus() {
    const billId = $('#updateBillId').text();
    const newStatus = $('#newStatus').val();
    if ([2, 3].includes(parseInt($('#currentStatus').data('status')))) {
        Swal.fire({
            icon: 'error',
            title: 'Không thể cập nhật!',
            text: 'Hóa đơn đã kết thúc, không thể cập nhật trạng thái!',
            confirmButtonText: 'Đã hiểu'
        });
        return;
    }
    $.ajax({
        url: '../../admin/API/index.php?type=updateBillStatus',
        method: 'POST',
        data: {
            MaHD: billId,
            TrangThai: newStatus
        },
        dataType: 'json',
        success: (response) => {
            alert('Cập nhật trạng thái thành công!');
            $('#updateStatusModal').modal('hide');
            loadBillList();
            // router.navigate('/bills'); 
        },
        error: (error) => {
            console.error('Lỗi khi cập nhật:', error);
            alert('Cập nhật trạng thái thất bại!');
        }
    });
}
function showBillDetail(billId) {
    console.log('Loading bill detail for ID:', billId);
    // if (window.router) {
    //     router.navigate(`/bills/detail/${billId}`); 
    // } else {
    //     console.error('Router not initialized');
    // }
    // Kiểm tra billId hợp lệ
    if (!billId || isNaN(billId)) {
        console.error('Invalid bill ID');
        return;
    }

    $('#billDetailList').html('<tr><td colspan="5" class="text-center">Đang tải...</td></tr>');
    $('.status-control').hide();
    try {
        $.ajax({
            url: `../../admin/API/index.php?type=getAllBillDetail&MaHD=${billId}`,
            method: 'GET',
            dataType: 'json',
            success: (detailData) => {
                // console.log('Detail data received:', detailData);
                $.ajax({
                    url: '../../admin/API/index.php?type=getAllBill',
                    method: 'GET',
                    dataType: 'json',
                    success: (billData) => {
                        // console.log('Bill data received:', billData);
                        const bill = billData.find(b => b.MaHD == billId);
                        if (bill) {
                            renderBillDetail(bill, detailData);
                            $('#billDetailModal').modal('show');
                        }
                    },
                    error: (err) => console.error('Error loading bill:', err)
                });
            },
            error: (err) => console.error('Error loading details:', err)
        });
    } catch (error) {
        console.error('Unexpected error:', error);
    }
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
}
// Các hàm hỗ trợ
function getStatusText(status) {
    console.log(status)
    const statusNum = Number(status);
    console.log(statusNum)
    if (isNaN(statusNum)) {
        console.error('Trạng thái không hợp lệ:', status);
        return "Không xác định";
    }

    switch (statusNum) {
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
handleBill()
document.addEventListener('DOMContentLoaded', () => {
        const permissionElement = document.getElementById('bill-permissions');
        const actionPermissions = {
        canView: permissionElement.dataset.canView ,
        canEdit: permissionElement.dataset.canEdit ,
        canDelete: permissionElement.dataset.canDelete ,
        canAdd: permissionElement.dataset.canAdd
    };

    console.log("Can Delete:", actionPermissions.canDelete);

});