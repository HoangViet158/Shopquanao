//format trạng thái
function formatStatus(statusid){
    console.log(statusid)
    switch(statusid.toLocaleString()){
        case '0':
            return 'Chưa xác nhận';
        case '1':
            return 'Đã xác nhận';
        case '2':
            return 'Đã giao thành công';
        case '3':
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}

//Thống kê hóa đơn
function loadInvoices(limit, offset, dayStart, dayEnd, id){
    
    let invoicesTable = document.getElementById('table-invoices');
    let table = `<thead>
                    <tr>
                        <th>Mã HD</th>
                        <th>Mã khách hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Thành tiền</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết hóa đơn</th>
                    </tr>
                </thead>`
    fetch(`../../admin/API/index.php?type=loadInvoices&limit=${limit}&offset=${offset}&daystart=${dayStart}&dayend=${dayEnd}&id=${id}`)
        .then(response => {
            if(!response.ok){
                throw new Error("Lỗi khi lấy dữ liệu")
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            data.forEach((invoice) =>{
            table += `<tr>
                        <td>${invoice.MaHD}</td>
                        <td>${invoice.MaTK}</td>
                        <td>${invoice.TenTK}</td>
                        <td>${Number(invoice.ThanhToan).toLocaleString()} VNĐ</td>
                        <td>${invoice.ThoiGian}</td>
                        <td>${formatStatus(invoice.TrangThai)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-success me-2" onclick="openInvoiceDetail(${invoice.MaHD})">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>`
        });
        invoicesTable.innerHTML = table; 
        })
        .catch(error => {
            console.error("Lỗi khi load hóa đơn:", error);
        });
}
function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0'); // Thêm số 0 nếu < 10
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}
let idUrl;
let daystartUrl;
let dayendUrl;
let limit = 10;
//load hóa đơn
document.addEventListener('DOMContentLoaded', () => {
    const urlParams  = new URLSearchParams(window.location.search);
    idUrl = urlParams.get('id');
    daystartUrl = urlParams.get('daystart');
    dayendUrl = urlParams.get('dayend');
    fetch(`../../admin/API/index.php?type=totalInvoices&daystart=${daystartUrl}&dayend=${dayendUrl}&id=${idUrl}`)
        .then(response =>{
            if(!response.ok){
                throw new Error("Lỗi khi lấy dữ liệu")
            }
            return response.json();
        })
        .then(total => 
            {console.log(total);
                loadInvoices(limit,0, daystartUrl, dayendUrl, idUrl)
                renderPagination(total.total,1)
            }
        )
        .catch(error => console.log('Error:', error));
})

//xem chi tiết hóa đơn
function openInvoiceDetail(id) {
    fetch(`../../admin/API/index.php?type=getAllBillDetail&MaHD=${id}`)
        .then(response => {
            if (!response.ok){
                throw new Error("Lỗi khi lấy dữ liệu")
            }
            return response.json()
        })
        .then(details => {
            renderInvoiceDetails(details)
        })
        .catch(error => {
            console.error("Lỗi khi load chi tiết hóa đơn: ", error)
        })
}

// Đóng Modal khi bấm X
document.querySelector('.close').onclick = function() {
    document.getElementById('invoiceModal').style.display = "none";
};

// Đóng Modal khi click ra ngoài
window.onclick = function(event) {
    const modal = document.getElementById('invoiceModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

// Hiển thị bảng chi tiết 
// function renderInvoiceDetails(details) {
//     const modal = document.getElementById('invoiceModal');
//     const detailTable = document.getElementById('invoiceDetails');

//     let table = `<thead>
//                     <tr>
//                         <th>Mã sản phẩm</th>
//                         <th>Tên sản phẩm</th>
//                         <th>Size</th>
//                         <th>Số lượng</th>
//                         <th>Đơn giá</th>
//                         <th>Thành tiền</th>
//                     </tr>
//                  </thead>`;

//     details.forEach(detail => {
//         table += `<tr>
//                     <td>${detail.MaSP}</td>
//                     <td>${detail.TenSP}</td>
//                     <td>${detail.TenSize}</td>
//                     <td>${detail.SoLuongBan}</td>
//                     <td>${Number(detail.DonGia).toLocaleString()} VNĐ</td>
//                     <td>${Number(detail.ThanhTien).toLocaleString()} VNĐ</td>
//                   </tr>`;
//     });

//     detailTable.innerHTML = table;
//     modal.style.display = "block"; // Mở modal ra
// }

let fullDetailList = []; // Toàn bộ chi tiết hóa đơn
let currentDetailPage = 1;
const detailPageSize = 3; // 3 sản phẩm / trang

function renderInvoiceDetails(details) {
    fullDetailList = details;
    currentDetailPage = 1;
    renderInvoiceDetailPage(currentDetailPage);

    const modal = document.getElementById('invoiceModal');
    modal.style.display = "block";
}

function renderInvoiceDetailPage(page) {
    const detailTable = document.getElementById('invoiceDetails');
    const start = (page - 1) * detailPageSize;
    const end = start + detailPageSize;
    const details = fullDetailList.slice(start, end);

    let table = `<thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                 </thead>
                 <tbody>`;

    details.forEach(detail => {
        table += `<tr>
                    <td>${detail.MaSP}</td>
                    <td>${detail.TenSP}</td>
                    <td>${detail.TenSize}</td>
                    <td>${detail.SoLuongBan}</td>
                    <td>${Number(detail.DonGia).toLocaleString()} VNĐ</td>
                    <td>${Number(detail.ThanhTien).toLocaleString()} VNĐ</td>
                  </tr>`;
    });

    table += `</tbody>
              <tfoot>
                <tr><td colspan="6" style="text-align:center;">${renderDetailPagination(Math.ceil(fullDetailList.length / detailPageSize), page)}</td></tr>
              </tfoot>`;

    detailTable.innerHTML = table;
}

function renderDetailPagination(totalPages, currentPage) {
    let html = `<nav><ul class="pagination justify-content-center">`;

    if (currentPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="gotoDetailPage(${currentPage - 1})">Previous</a></li>`;
    } else {
        html += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
    }

    if (totalPages <= 5) {
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="gotoDetailPage(${i})">${i}</a></li>`;
            }
        }
    } else {
        for (let i = 1; i <= 3; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="gotoDetailPage(${i})">${i}</a></li>`;
            }
        }
        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        if (currentPage === totalPages) {
            html += `<li class="page-item active"><span class="page-link">${totalPages}</span></li>`;
        } else {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="gotoDetailPage(${totalPages})">${totalPages}</a></li>`;
        }
    }

    if (currentPage < totalPages) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="gotoDetailPage(${currentPage + 1})">Next</a></li>`;
    } else {
        html += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
    }

    html += `</ul></nav>`;

    return html;
}

function gotoDetailPage(page) {
    currentDetailPage = page;
    renderInvoiceDetailPage(page);
}

// Phân trang
function renderPagination(totalItems, currentPage) {
    const pagination = document.getElementById('invoice-detail-pagination');
    const totalPages = Math.ceil(totalItems / limit);

    let html = '';

    if (currentPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="prev">Previous</a></li>`;
    }
    else {
        html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="prev">Previous</a></li>`;

    }

    if (totalPages <= 5) {
        // Nếu tổng số trang nhỏ hơn 5, in hết
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
    } else if(currentPage +4 >= totalPages){
        for (let i =  totalPages - 4; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
    }
    else {
        // Nếu tổng số trang lớn hơn 5
        for (let i = currentPage; i <= currentPage + 2; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else if (i <= totalPages){
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        if (currentPage + 2 < totalPages){
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
        }
    }

    if (currentPage < totalPages) {
        html += `<li class="page-item"><a class="page-link" href="#" data-page="next">Next</a></li>`;
    }
    else {
        html += `<li class="page-item disabled"><a class="page-link" href="#" data-page="next">Next</a></li>`;
    }

    pagination.innerHTML = html;

    // Gán sự kiện click cho các phần tử sau khi render
    const pageItems = document.querySelectorAll('.page-item a');
    pageItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Ngừng tải lại trang
            const page = this.getAttribute('data-page');
            if (page === 'prev') {
                // Xử lý khi nhấn nút "Previous"
                if (currentPage > 1) {
                    renderPagination(totalItems, currentPage - 1);
                    loadInvoices(limit,currentPage-2,daystartUrl,dayendUrl,idUrl);
                }
            } else if (page === 'next') {
                // Xử lý khi nhấn nút "Next"
                if (currentPage < totalPages) {
                    renderPagination(totalItems, currentPage + 1);
                    loadInvoices(limit,currentPage,daystartUrl,dayendUrl,idUrl);
                }
            } else {
                // Xử lý khi nhấn vào trang cụ thể
                renderPagination(totalItems, parseInt(page));
                loadInvoices(limit,parseInt(page)-1,daystartUrl,dayendUrl,idUrl)

            }
        });
    });
}


