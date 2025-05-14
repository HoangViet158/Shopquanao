function handleGoodsReceipt(){
    const Mange_client=document.getElementsByClassName("Mange_client")[0]
    const handleGoodsReceiptOut=`
    <div class="container-fluid">
        <div class="toolbar mb-3">
            <div class="input-group" style="width:300px;">
                <input type="text" class="form-control" placeholder="Tìm kiếm">
                <button class="btn" style="background-color:#89cff0; background-border:#89cff0;color:black;" onClick="searchProduct()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <button class="btn" style="background-color:#89cff0; background-border:89cff0;color:black;" onclick="showAddGoodReceiptForm()">
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
                                <th>Người tạo</th>
                                <th>Tên nhà cung cấp</th>
                                <th>Thanh toán</th>
                                <th>Thời gian</th>
                                <th>Xem chi tiết</th>
                            </tr>
                        </thead>
                        <tbody id="good-receipt-list"></tbody>
                    </table>
                </div>
                
            </div>
        </div>
        <!--phần thêm phiếu nhập-->
        <div class="modal fade" tableindex="-1" aria-hidden="false" id="addProductModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGoodReceipt">Thêm phiếu nhập mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addGoodReceiptForm">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center">
                                        <label for="provider" class="form-label me-2" style="min-width: 150px;">Tên nhà cung cấp</label>
                                        <select class="form-select flex-grow-1" id="provider" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Size</th>
                                            <th>Số lượng nhập</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody"></tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary mb-3" onclick="addProductRow()">
                                <i class="fas fa-plus"></i>Thêm sản phẩm
                            </button>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <label for="totalPay" class="form-label me-2" style="min-width: 150px;">Tổng tiền thanh toán</label>
                                        <input type="text" id="totalPay" class="form-control flex-grow-1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <label for="profitPercentage" class="form-label">% Lợi nhuận</label>
                                        <input type="number" class="form-control" id="profitPercentage" min="0" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                             <!-- Thêm phần hiển thị giá bán -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Giá bán đề xuất</h5>
                                </div>
                                <div class="card-body">
                                    <div id="suggestedPrices"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onclick="submitGoodReceiptForm()">Lưu phiếu nhập</button>
                    </div>
                </div>
            
            </div>
        
        </div>

    </div>
    `
    Mange_client.innerHTML=handleGoodsReceiptOut;
    document.getElementById('profitPercentage').addEventListener('input', function() {
        calculateSuggestedPrices();
        calculateTotalPayment();
    });
    loadGoodsReceiptList()
}

async function calculateSuggestedPrices() {
    const profitPercentage = parseFloat(document.getElementById('profitPercentage').value) || 0;
    const productRows = document.querySelectorAll("#productTableBody tr");
    
    if (productRows.length === 0 || isNaN(profitPercentage)) {
        document.getElementById('suggestedPrices').innerHTML = '';
        return;
    }

    const products = {};

    productRows.forEach(row => {
        const productId = row.querySelector('.product-name').value;
        const productName = row.querySelector('.product-name option:checked').text;
        const sizeId = row.querySelector('.product-size').value;
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const importPrice = parseFloat(row.querySelector('.price').value) || 0;

        // Nếu thiếu thông tin, bỏ qua hàng này
        if (!productId || !sizeId || quantity <= 0 || importPrice <= 0) return;

        if (!products[productId]) {
            products[productId] = {
                productName: productName,
                items: []
            };
        }

        products[productId].items.push({
            MaSize: sizeId,
            SoLuongNhap: quantity,
            DonGia: importPrice
        });
    });

    // Không có sản phẩm hợp lệ => không gửi request
    if (Object.keys(products).length === 0) {
        document.getElementById('suggestedPrices').innerHTML = '';
        return;
    }

    try {
        const response = await fetch('../../admin/API/index.php?type=calculateSuggestedPrices', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                ProfitPercentage: profitPercentage,
                products: products
            })
        });

        const result = await response.json();

        if (!result.success) {
            document.getElementById('suggestedPrices').innerHTML = `
                <div class="alert alert-danger">${result.message}</div>`;
            return;
        }

        const container = document.getElementById('suggestedPrices');
        const productList = result.data;

        let html = `<div class="table-responsive"><table class="table table-bordered">
        <thead><tr>
            <th>Tên sản phẩm</th>
            <th>Giá nhập trung bình</th>
            <th>Khuyến mãi %</th>
            <th>Lợi nhuận %</th>
            <th>Giá bán đề xuất</th>
        </tr></thead><tbody>`;

        for (const product of productList) {
            html += `<tr>
                <td>${product.productName}</td>
                <td>${Number(product.averagePrice).toLocaleString()}</td>
                <td>${product.discount}%</td>
                <td>${profitPercentage}%</td>
                <td>${Number(product.suggestedPrice).toFixed(0).toLocaleString()}</td>
            </tr>`;
        }

        html += '</tbody></table></div>';
        container.innerHTML = html;
    } catch (error) {
        console.error(error);
        document.getElementById('suggestedPrices').innerHTML = `
            <div class="alert alert-danger">Đã xảy ra lỗi khi lấy giá đề xuất.</div>`;
    }
}
function submitGoodReceiptForm() {
    calculateSuggestedPrices();
    const errorDiv = document.querySelector("#suggestedPrices .alert-danger");

    if (errorDiv) {
        alert("Vui lòng sửa các lỗi trước khi lưu");
        return;
    }

    const providerId = document.getElementById('provider').value;
    const profitPercentage = parseFloat(document.getElementById('profitPercentage').value) || 0;

    if (!providerId) {
        alert('Vui lòng chọn nhà cung cấp');
        return;
    }

    const productRows = document.querySelectorAll("#productTableBody tr");
    if (productRows.length === 0) {
        alert('Vui lòng thêm ít nhất 1 sản phẩm');
        return;
    }

    const productsGrouped = {};
    let isValid = true;

    productRows.forEach(row => {
        const productId = row.querySelector('.product-name').value;
        const productName = row.querySelector('.product-name option:checked').text;
        const sizeId = row.querySelector('.product-size').value;
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const importPrice = parseFloat(row.querySelector('.price').value) || 0;
        const subtotal = parseFloat(row.querySelector('.subtotal').value.replace(/,/g, '')) || 0;

        if (!productId || !sizeId || quantity <= 0 || importPrice <= 0) {
            isValid = false;
            return;
        }

        if (!productsGrouped[productId]) {
            productsGrouped[productId] = {
                productName: productName,
                items: []
            };
        }

        productsGrouped[productId].items.push({
            MaSize: sizeId,
            SoLuongNhap: quantity,
            DonGia: importPrice,
            ThanhTien: subtotal
        });
    });

    if (!isValid) {
        alert('Vui lòng điền đầy đủ thông tin cho tất cả sản phẩm');
        return;
    }

    const totalPay = document.getElementById('totalPay').value.replace(/,/g, '');

    const data = {
        MaNCC: providerId,
        products: productsGrouped,
        TongTien: totalPay,
        ProfitPercentage: profitPercentage
    };

    $.ajax({
        url: '../../admin/API/index.php?type=addGoodReceipt',
        method: 'POST',
        dataType: 'json',
        data: {
            MaNCC: providerId,
            products: JSON.stringify(productsGrouped),
            TongTien: totalPay,
            ProfitPercentage: profitPercentage
        },
        success: function(response) {
            try {
                const result = typeof response === 'string' ? JSON.parse(response) : response;
                if (result.success) {
                    alert(result.message);
                    $('#addProductModal').modal('hide');
                    loadGoodsReceiptList();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (e) {
                alert('Thêm phiếu nhập thành công');
                $('#addProductModal').modal('hide');
                loadGoodsReceiptList();
            }
        },
        error: function(error) {
            try {
                const errResponse = JSON.parse(error.responseText);
                alert('Lỗi: ' + (errResponse.message || 'Có lỗi xảy ra khi thêm phiếu nhập'));
            } catch (e) {
                alert('Có lỗi xảy ra khi thêm phiếu nhập');
            }
        }
    });
}
// Thêm kiểm tra khi thêm sản phẩm mới
function addProductRow() {
    const productTableBody = document.getElementById('productTableBody');
    const newRow = productTableBody.insertRow();
    
    newRow.innerHTML = `
    <td>
        <select class="form-select product-name" required>
            <option value="">Chọn sản phẩm</option>
        </select>
    </td>
    <td>
        <select class="form-select product-size" required>
            <option value="">Chọn size</option>
        </select>
    </td>
    <td>
        <input type="number" class="form-control quantity" min="1" value="1" required>
    </td>
    <td>
        <input type="number" class="form-control price" min="0" value="0" required>
    </td>
    <td>
        <input type="text" class="form-control subtotal" readonly>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(this)">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    `;

    loadProductOptions(newRow);
    loadSizeOptions(newRow);

    const productSelect = newRow.querySelector('.product-name');
    const priceInput = newRow.querySelector('.price');
    const quantityInput = newRow.querySelector('.quantity');
    const sizeSelect = newRow.querySelector('.product-size');

    // Thêm sự kiện kiểm tra trùng lặp
    const checkDuplicate = () => {
        const currentProduct = productSelect.value;
        const currentSize = sizeSelect.value;
        
        if (!currentProduct || !currentSize) return;
        
        const rows = document.querySelectorAll("#productTableBody tr");
        let duplicateCount = 0;
        
        rows.forEach(row => {
            const p = row.querySelector('.product-name').value;
            const s = row.querySelector('.product-size').value;
            if (p === currentProduct && s === currentSize) {
                duplicateCount++;
            }
        });
        
        if (duplicateCount > 1) {
            newRow.style.border = "2px solid red";
            calculateSuggestedPrices(); // Cập nhật thông báo lỗi
        } else {
            newRow.style.border = "";
            calculateSuggestedPrices();
        }
    };

    productSelect.addEventListener('change', checkDuplicate);
    sizeSelect.addEventListener('change', checkDuplicate);
    priceInput.addEventListener('input', function(e) {
        calculateRowTotal(e);
        calculateSuggestedPrices();
    });
    quantityInput.addEventListener('input', function(e) {
        calculateRowTotal(e);
    });
}
function removeProductRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotalPayment();
    calculateSuggestedPrices(); // Cập nhật lại giá bán đề xuất khi xóa hàng
}
function calculateRowTotal(event){
    const row = event.target.closest('tr');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const subtotal = row.querySelector('.subtotal');
    const total = quantity * price;
    subtotal.value = isNaN(total) ? "0" : total.toLocaleString();
    calculateTotalPayment();
    calculateSuggestedPrices(); // Thêm dòng này để tính lại giá bán đề xuất
}
function calculateTotalPayment(){
    const subtotals=document.querySelectorAll('.subtotal')
    let totalPayment=0
    subtotals.forEach(subtotal=>{
        const value = parseFloat(subtotal.value.replace(/,/g, ''));
        if(!isNaN(value)){
            totalPayment+=value
        }
    })
    document.getElementById('totalPay').value=totalPayment.toLocaleString()
}
function loadProductOptions(row){
    $.ajax({
        url:'../../admin/API/index.php?type=getAllTenSP',
        method:'GET',
        dataType:'json',
        success: function(data){
            const select=row.querySelector('.product-name')
            data.forEach(product=>{
                const option=document.createElement('option')
                option.value=product.MaSP
                option.textContent=product.TenSP
                select.appendChild(option)
            })
        },
        error: function(error){
            console.error("Lỗi khi load product",error)
        }
    })
}
function loadSizeOptions(row){
    $.ajax({
        url:'../../admin/API/index.php?type=getAllSize',
        method:'GET',
        dataType:'json',
        success: function(data){
            const select=row.querySelector('.product-size')
            data.forEach(size=>{
                const option=document.createElement('option')
                option.value=size.MaSize
                option.textContent=size.TenSize
                select.appendChild(option)
            })
        },
        error: function (error){
            console.error("loi khi load size",error)
        }
    })
}
function showAddGoodReceiptForm(){
    if(!actionPermissions.canAdd){
           Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không có quyền hạn truy cập chức năng này!',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
    }
    $.ajax({
        url:'../../admin/API/index.php?type=getAllProvider',
        method:'GET',
        dataType:'json',
        success: function(data){
            const select=document.getElementById('provider')
            select.innerHTML='<option value="">Chọn nhà cung cấp</option>'
            data.forEach(provider=>{
                const option=document.createElement('option')
                option.value=provider.MaNCC
                option.textContent=provider.TenNCC
                select.appendChild(option)
            })
            document.getElementById('productTableBody').innerHTML=''
            addProductRow()
            document.getElementById('totalPay').value="0"
            $('#addProductModal').modal('show')
        },
        error: function(error){
            console.error("khong the lay thong tin nha cung cap ",error)
        }
    })
}
function loadGoodsReceiptList(){
    $.ajax({
        url:'../../admin/API/index.php?type=loadGoodsReceiptList',
        method:'GET',
        dataType:'json',
        success: (data) =>{
            renderGoodsReceiptList(data)
        },
        error:(error) =>{
            console.error("loi khi tai phieu nahp" +error)
        }
    })
}
function renderGoodsReceiptList(data){
    const goodReceiptTable=$("#good-receipt-list")
    goodReceiptTable.empty()
    if(data.length===0){
        goodReceiptTable.append('<tr><td class="text-center py-4">Không có phiếu nhập nào</td></tr>')
        return;
    }
    data.forEach(datum=>{
        const row=`
        <tr>
            <td>${datum.MaPN}</td>
            <td>${datum.taikhoan.TenTK}</td>
            <td>${datum.nhacungcap.TenNCC}</td>
            <td>${datum.ThanhToan}</td>
            <td>${datum.ThoiGian}</td>
            <td>
                <button class="btn btn-sm btn-outline-success me-2" onclick="showGoodsReceiptDetailList(${datum.MaPN})">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
        `
        goodReceiptTable.append(row)
    })
}
function showGoodsReceiptDetailList(maPN){
    const Mange_client=document.getElementsByClassName("Mange_client")[0]
    const receiptDetails=
    `
    <div class="container-fluid">
        <div class="toolbar mb-3">
            <div class="input-group" style="width:300px;">
                <input type="text" class="form-control" placeholder="Tìm kiếm">
                <button type="button" class="btn" style="background-color:#89cff0;background-border:#89cff0;color:black;">
                    <i class="fas fa-search"></i>
                </button> 
            </div>
            <button type=button class="btn" style="background-color:#89cff0;background-border:#89cff0;color:black;" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus">
                    <span>Thêm mới</span>
                </i>
            </button>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã PN</th>
                                <th>Tên sản phẩm</th>
                                <th>Tên size</th>
                                <th>Đơn giá</th>
                                <th>Số lượng nhập</th>
                                <th>Thành tiền</th>
                            </tr>
                        
                        </thead>
                        <tbody id="goodsReceiptDetail"></tbody>
                    </table>

                
                </div>
                <button class="btn mt-4" style="background-color:#89cff0; background-border:#89cff0;color:black;" onclick="handleGoodsReceipt()">Trở về</button>
            </div>
        
        </div>
    </div>
    
    `
    Mange_client.innerHTML=receiptDetails
    loadGoodsReceiptDetailList(maPN)
}
function loadGoodsReceiptDetailList(maPN){
    const url=`../../admin/API/index.php?type=getGoodReceiptDetail&MaPN=${maPN}`
    $.ajax({
        url:url,
        method:'GET',
        dataType:'json',
        success: (data)=>{
            renderGoodsReceiptDetailList(data)
        },
        error: (error)=>{
            console.error("loi khi load chi tiet phieu nhap" , error)
        }
    })
}
function renderGoodsReceiptDetailList(data){
    const detailTable=$("#goodsReceiptDetail")
    detailTable.empty()
    if(data.length===0){
        detailTable.append('<tr><td>Không có chi tiết phiếu nhập</td></tr>')
        return
    }
    data.forEach(datum=>{
        const row=`
            <tr>
                <td>${datum.MaPN}</td>
                <td>${datum.sanpham.TenSP}</td>
                <td>${datum.size.TenSize}</td>
                <td>${datum.DonGia}</td>
                <td>${datum.SoLuongNhap}</td>
                <td>${datum.ThanhTien}</td>
            </tr>
        `
        detailTable.append(row)
    })
}
function submitGoodReceiptForm(){
    calculateSuggestedPrices();
    const errorDiv = document.querySelector("#suggestedPrices .alert-danger");
    const warningDiv = document.querySelector("#suggestedPrices .alert-warning");
    
    if (errorDiv) {
        alert("Vui lòng sửa các lỗi trước khi lưu");
        return;
    }
    
    if (warningDiv && !confirm("Một số sản phẩm có giá bán thấp hơn giá nhập. Bạn có chắc muốn tiếp tục?")) {
        return;
    }
    const providerId = document.getElementById('provider').value;
    const profitPercentage = parseFloat(document.getElementById('profitPercentage').value) || 0;
    
    if (!providerId) {
        alert('Vui lòng chọn nhà cung cấp');
        return;
    }

    const productRows = document.querySelectorAll("#productTableBody tr");
    if (productRows.length === 0) {
        alert('Vui lòng thêm ít nhất 1 sản phẩm');
        return;
    }

    // Nhóm sản phẩm theo MaSP và thu thập thông tin
    const productsGrouped = {};
    let isValid = true;

    productRows.forEach(row => {
        const productId = row.querySelector('.product-name').value;
        const sizeId = row.querySelector('.product-size').value;
        const quantity = row.querySelector('.quantity').value;
        const importPrice = row.querySelector('.price').value;
        const subtotal = row.querySelector('.subtotal').value.replace(/,/g, '');

        if (!productId || !sizeId || !quantity || !importPrice) {
            isValid = false;
            return;
        }

        if (!productsGrouped[productId]) {
            productsGrouped[productId] = {
                productName: row.querySelector('.product-name option:checked').text,
                maxImportPrice: 0,
                items: []
            };
        }

        // Cập nhật giá nhập cao nhất
        const price = parseFloat(importPrice);
        if (price > productsGrouped[productId].maxImportPrice) {
            productsGrouped[productId].maxImportPrice = price;
        }

        productsGrouped[productId].items.push({
            MaSize: sizeId,
            SoLuongNhap: quantity,
            DonGia: importPrice,
            ThanhTien: subtotal
        });
    });

    if (!isValid) {
        alert('Vui lòng điền đầy đủ thông tin cho tất cả sản phẩm');
        return;
    }

    // Gửi dữ liệu lên server
    const data = {
        MaNCC: providerId,
        products: productsGrouped,
        TongTien: document.getElementById('totalPay').value.replace(/,/g, ''),
        ProfitPercentage: profitPercentage
    };
    
    $.ajax({
        url: '../../admin/API/index.php?type=addGoodReceipt',
        method: 'POST',
        dataType: 'json',
        data: {
            MaNCC: providerId,
            products: JSON.stringify(productsGrouped),  // ⚡ Convert sang JSON string
            TongTien: document.getElementById('totalPay').value.replace(/,/g, ''),
            ProfitPercentage: profitPercentage
        },
        success: function(response) {
            alert('Thêm phiếu nhập thành công');
            $('#addProductModal').modal('hide');
            loadGoodsReceiptList();
        },
        error: function(error) {
            alert('Có lỗi xảy ra khi thêm phiếu nhập: ' + error.responseText);
            console.error("Lỗi khi thêm phiếu nhập", error);
        }
    });
}
// 
handleGoodsReceipt()

document.addEventListener('DOMContentLoaded', () => {
        const permissionElement = document.getElementById('goodReceipt-permissions');
        const actionPermissions = {
        canView: permissionElement.dataset.canView ,
        canEdit: permissionElement.dataset.canEdit ,
        canDelete: permissionElement.dataset.canDelete ,
        canAdd: permissionElement.dataset.canAdd
    };

    console.log("Can Delete:", actionPermissions.canDelete);

});
