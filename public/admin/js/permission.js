async function getAllPermission() {
    const res = await fetch(`../../admin/API/index.php?type=getAllPermission`)
    if (!res.ok) throw new Error('Lỗi khi lấy tất cả quyền')
    const permissions = await res.json();
    console.log(permissions)
    RenderPermissionList(permissions);
}
async function RenderPermissionList(permissionlist) {
    const permissionTable = document.getElementById('table-permission');
    let table = `<thead>
                    <tr>
                        <th>Mã Quyền</th>
                        <th>Tên Quyền </th>
                        <th>Thao tác</th>
                    </tr>
                 </thead>
                 <tbody>`;
    permissionlist.forEach((permission) => {
    table += `<tr id="permission-row-${permission.MaQuyen}">
                <td>${permission.MaQuyen}</td>
                <td>${permission.TenQuyen}</td>
                <td>
                    <button class="btn btn-sm btn-outline-success me-2" onclick="openEditPermission(${permission.MaQuyen})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deletePermission(${permission.MaQuyen})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
    });

    table += `</tbody>`;
    permissionTable.innerHTML = table;
}

//add Permission
// tham chiếu đến modal và nút đóng
const addPermissionModal  = document.getElementById('addPermissionModal');
const addPermissionClose  = document.getElementById('addPermissionClose');
const addPermissionForm   = document.getElementById('addPermissionForm');

// Hàm mở modal
function openAddPermissionModal() {
  addPermissionModal.style.display = 'block';
}

// Khi bấm nút ×, hỏi xác nhận rồi mới đóng
addPermissionClose.onclick = () => {
    Swal.fire({
        icon: 'question',
        title: 'Bạn muốn tiếp tục?',
        text: 'Thay đổi chưa được lưu sẽ bị mất.',
        showCancelButton: true,
        confirmButtonText: 'Tiếp tục',
        cancelButtonText: 'Quay lại'
      })
    .then((result) => {
        if (result.isConfirmed){
            addPermissionModal.style.display = 'none';
            addPermissionForm.reset();
        }
    })
};

// Khi click ra ngoài modal-content, hỏi xác nhận rồi mới đóng
window.addEventListener('click', e => {
  if (e.target === addPermissionModal) {
    Swal.fire({
        icon: 'question',
        title: 'Bạn muốn tiếp tục?',
        text: 'Thay đổi chưa được lưu sẽ bị mất.',
        showCancelButton: true,
        confirmButtonText: 'Tiếp tục',
        cancelButtonText: 'Quay lại'
      })
    .then((result) => {
        if (result.isConfirmed){
            addPermissionModal.style.display = 'none';
            addPermissionForm.reset();
        }
    })
  }
});

// Xử lý submit form thêm Permission (ví dụ dùng fetch POST)
addPermissionForm.addEventListener('submit', async e => {
  e.preventDefault();
  const formData = new FormData(addPermissionForm);
  const data = Object.fromEntries(formData.entries());
//   console.log(data)
  if(!data.tenQuyen.trim()){
      Swal.fire({
      icon: 'warning',
      title: 'Cảnh báo!',
      text: 'Tên quyền không được để trống',
      confirmButtonText: 'Đã hiểu'
    });  
    return     
  }

  try {
    const res = await fetch('../../admin/API/index.php?type=addPermission', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const json = await res.json();
    // console.log(json)
    if (json.success) {
        Swal.fire({
            icon: 'success',
            title: 'Thông báo!',
            text: 'Thêm quyền thành công',
            confirmButtonText: 'Đã hiểu'
          });       
        addPermissionModal.style.display = 'none';
        addPermissionForm.reset();
        getAllPermission();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Thông báo!',
            text: 'Thêm quyền thất bại',
            confirmButtonText: 'Đã hiểu'
          }); 
    }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối.');
  }
});
//validate cho xóa quyền
async function validateDeletePermission(id) {
    const res = await fetch(`../../admin/API/index.php?type=getPermissionById&id=${id}`)
    if (!res.ok) throw new Error('lỗi khi lấy permission');
    const permission = await res.json();
    // console.log(permission)
    if (permission.TenQuyen == "Admin" || permission.TenQuyen == "Khách hàng"){
        Swal.fire({
            icon: "error",
            title: 'Cảnh báo',
            text: 'Không được xóa quyền Admin hoặc khách hàng',
            confirmButtonText: 'Đã hiểu'
        })
        return true;
    }
    return false;
}

//mở trang chi tiết quyền chức năng
function openEditPermission(id){
  window.location.href = `permission-detail.php?id=${id}`
}

//Xóa quyền
async function deletePermission(id) {
  const isInvalid = await validateDeletePermission(id);
  if (isInvalid) {
    return;
  }
    Swal.fire({
        icon: 'warning',
        title: 'Xóa quyền?',
        text: 'Các user đang dùng quyền này sẽ được chuyển sang quyền mặc định (Khách hàng).',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then(async (result) => {
        if (result.isConfirmed){
            const res = await fetch(`../../admin/API/index.php?type=deletePermission&id=${id}`)
            if (!res.ok) throw new Error('Lỗi khi xóa quyèn')
            const result = await res.json();
            // console.log(result)
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thông báo!',
                    text: 'Xoá quyền thành công',
                    confirmButtonText: 'Đã hiểu'
                  });       
                addPermissionModal.style.display = 'none';
                addPermissionForm.reset();
                getAllPermission();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Thông báo!',
                    text: 'Xoá quyền thất bại',
                    confirmButtonText: 'Đã hiểu'
                  }); 
            }
        }
    })
}
//Tìm kiếm theo tên 
function searchPermission(){
  console.log('click')
   const key = document.getElementById('searchInput').value.toLowerCase();
   const table = document.getElementById('table-permission');
   const rows = table.getElementsByTagName('tr');

   for (let i = 1; i < rows.length; i++) { // Bắt đầu từ dòng 1 để bỏ qua tiêu đề
    const row = rows[i];
    const cell = row.getElementsByTagName('td')[1]; // Lấy cột tên tài khoản (index = 1)

    if (cell) {
        const textValue = cell.textContent || cell.innerText; // Lấy nội dung của cột
        // Kiểm tra nếu nội dung cột chứa từ khóa tìm kiếm
        if (textValue.toLowerCase().indexOf(key) > -1) {
            row.style.display = ''; // Hiển thị dòng nếu khớp
        } else {
            row.style.display = 'none'; // Ẩn dòng nếu không khớp
        }
    }
}
  
}
// Khi trang load xong, khởi tạo
document.addEventListener('DOMContentLoaded', () => {
    getAllPermission()
});
