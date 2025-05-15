//Lấy danh sách chức năng
async function getAllFunction() {
    const res = await fetch(`../../admin/API/index.php?type=getAllFunction`);
    if (!res.ok) throw new Error('Lỗi khi lấy tất cả chức năng')
    const functions = await res.json();
    console.log(functions)
    return functions;
}
//Lấy danh sách hành động
async function getAction(MaQuyen,MaCTQ) {
    const res = await fetch(`../../admin/API/index.php?type=getAction&maquyen=${MaQuyen}&mactq=${MaCTQ}`);
    if (!res.ok) throw new Error('Lỗi khi lấy hành động');
    const actions = await res.json();
    return actions;
}

//render table
async function renderPermissionDetail(functionList){
    const tablePermissionDetail = document.getElementById('table-permission-detail');
    let table = `<thead>
                    <tr>
                        <th> Chức năng </th>
                        <th> Thêm </th>
                        <th> Xem </th>
                        <th> Sửa </th>
                        <th> Xóa </th>
                    </tr> 
                </thead>
                <tbody>`
    functionList.forEach(element => {
        table += `<tr id="function-row-${element.MaCTQ}">
                    <td>${element.ChucNang}</td>
                    <td><input type="checkbox" class="actionCheckbox" value="add"></td>
                    <td><input type="checkbox" class="actionCheckbox" value="view"></td>
                    <td><input type="checkbox" class="actionCheckbox" value="edit"></td>
                    <td><input type="checkbox" class="actionCheckbox" value="delete"></td>
                </tr>`
    }); 
    table += `</tbody>`;
    tablePermissionDetail.innerHTML = table;
    for (const element of functionList) {
        const actionResponse = await getAction(idUrl, element.MaCTQ);

        // Kiểm tra xem mảng có phần tử hay không
        if (actionResponse.length > 0) {
            // Truy cập phần tử đầu tiên trong mảng và lấy thuộc tính HanhDong
            const hanhDong = actionResponse[0].HanhDong;
            setCheckboxesFromAction(element.MaCTQ, hanhDong)
        }
        document.querySelector(`#function-row-${element.MaCTQ}`).querySelectorAll('.actionCheckbox')
        .forEach(checkbox => {
            checkbox.addEventListener('click', function (e) {
                const currentValues = getValueFromCheckBoxRow(element.MaCTQ) || [];
                const isViewChecked = currentValues.includes('view');
                const checkboxValue = checkbox.value;
                if(parseInt(idUrl) == 1 || parseInt(idUrl) == 3){
                    e.preventDefault(); 
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cảnh báo',
                        text: 'Không được chỉnh sửa quyền Admin và Khách hàng',
                        confirmButtonText: 'Đã hiểu'
                    })
                    return;
                }
                // Ngăn tích checkbox khác nếu chưa có quyền 'view'
                if (!isViewChecked && checkboxValue !== 'view') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Cần cấp hành động xem cho chức năng này trước!',
                        confirmButtonText: 'Đã hiểu'
                    });
                    return;
                }
                editPermissionDetail(idUrl, element.MaCTQ, getValueFromCheckBoxRow(element.MaCTQ))
            });
        });
    }
}
// Cập nhật chi tiết quyền chức năng
async function editPermissionDetail(MaQuyen, MaCTQ, actions) {
    // Tạo đối tượng dữ liệu để gửi đi
    const databody = {
        'maquyen': MaQuyen,
        'mactq': MaCTQ,
        'hanhdong': actions // Chuyển mảng hành động thành chuỗi phân tách bằng dấu phẩy
    };

    console.log(databody);

    try {
        const res = await fetch(`../../admin/API/index.php?type=editPermission`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(databody)  // Chuyển đối tượng thành chuỗi JSON
        });

        if (!res.ok) {
            throw new Error('Lỗi khi sửa chi tiết quyền chức năng');
        }

        const data = await res.json();
        console.log(data); // Kiểm tra dữ liệu trả về từ API

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thông báo!',
                text: 'Sửa thành công',
                confirmButtonText: 'Đã hiểu'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Sửa thất bại',
                confirmButtonText: 'Đã hiểu'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể gửi yêu cầu',
            confirmButtonText: 'Đã hiểu'
        });
    }
}

// Check cho những hành động của quyền đã được tạo
function setCheckboxesFromAction(id,hanhDong) {
    
    const row = document.querySelector(`#function-row-${id}`)
    if (!row) {
        return; // Dừng hàm nếu không tìm thấy dòng
    }
    console.log(row)
    if (!hanhDong) {
        // console.warn(`Không có hành động cho MaCTQ: ${id}`);
        return; // Nếu không có hành động, thoát khỏi hàm
    }
    const actions = hanhDong.split(','); // ["view", "edit"]
    const checkboxes = row.querySelectorAll('.actionCheckbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = actions.includes(checkbox.value);
    });
}

// Lấy hành động của các ô đã được đánh dấu checkcheck
function getValueFromCheckBoxRow(id){
    actions = [];
    const row = document.querySelector(`#function-row-${id}`)
    console.log(row)
    const checkboxes = row.querySelectorAll('.actionCheckBox');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked){
            actions.push(checkbox.value)
        }
    })
    if (actions.length > 1)
       {return actions.join(',');} 
    else{
        return actions[0];
    }
}
let idUrl;
// Gọi khi trang load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams  = new URLSearchParams(window.location.search);
    idUrl = urlParams.get('id');
    getAllFunction().then(data => {
    renderPermissionDetail(data);
    });
});