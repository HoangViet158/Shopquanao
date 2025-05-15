// Định nghĩa biến toàn cục
let limit = 5;
let totalUsers = 0;
let searchName = "";

// const permissionElement = document.getElementById('user-permissions');

//     const actionPermissions = {
//         canView: permissionElement.dataset.canView === "1",
//         canEdit: permissionElement.dataset.canEdit === "1",
//         canDelete: permissionElement.dataset.canDelete === "1",
//         canAdd: permissionElement.dataset.canAdd === "1"
//     };

// Format trạng thái user

// Validate dữ liệu
async function validateAddUserForm() {
    const tenTK = document.querySelector('input[name="TenTK"]').value.trim();
    const matKhau = document.querySelector('input[name="MatKhau"]').value;
    const gmail = document.querySelector('input[name="Email"]').value.trim();
    const address = document.querySelector('input[name="DiaChi"]').value.trim();

    if (tenTK === '') {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không được để trống tên tài khoản!',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    if (matKhau === '') {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không được để trống mật khẩu!',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    if (/\s/.test(matKhau)) {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Mật khẩu không được chứa khoảng trắng!',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.com$/;
    if (!emailRegex.test(gmail)) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo!',
            text: 'Bạn đã nhập sai định dạng email',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    if (address === '') {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không được để trống địa chỉ!',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    try {
        const response = await fetch(`../../admin/API/index.php?type=checkEmailExist&email=${encodeURIComponent(gmail)}`);
        if (!response.ok) throw new Error('Lỗi khi lấy dữ liệu');
        const json = await response.json();

        if (json.success) {
            Swal.fire({
                icon: 'error',
                title: 'Không được phép!',
                text: 'Email đã tồn tại!',
                confirmButtonText: 'Đã hiểu'
            });
            return false;
        }
    } catch (error) {
        console.error('Lỗi khi kiểm tra email:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể kiểm tra email.',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    return true;
}

async function validateEditUserForm() {
    const tenTK = document.getElementById('editTenTK').value.trim();
    const matKhau = document.getElementById('editMatKhau').value;
    const address = document.getElementById('editDiaChi').value.trim();
    const gmail = document.getElementById('editEmail').value.trim();

     if (tenTK === '') {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không được để trống tên tài khoản!',
            confirmButtonText: 'Đã hiểu'
          });          
        return false;
    }
     if (address === '') {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không được để trống địa chỉ!',
            confirmButtonText: 'Đã hiểu'
          });          
        return false;
    }

    // if (matKhau === '') {
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Không được phép!',
    //         text: 'Bạn không được để trống mật khẩu!',
    //         confirmButtonText: 'Đã hiểu'
    //       });
    //     return false;
    // }

    if (/\s/.test(matKhau)) {
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Mật khẩu không được chứa khoảng trắng!',
            confirmButtonText: 'Đã hiểu'
          });
        return false;
    }

      try {
        const response = await fetch(`../../admin/API/index.php?type=checkEmailExist&email=${encodeURIComponent(gmail)}`);
        if (!response.ok) throw new Error('Lỗi khi lấy dữ liệu');
        const json = await response.json();

        if (json.success) {
            Swal.fire({
                icon: 'error',
                title: 'Không được phép!',
                text: 'Email đã tồn tại!',
                confirmButtonText: 'Đã hiểu'
            });
            return false;
        }
    } catch (error) {
        console.error('Lỗi khi kiểm tra email:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể kiểm tra email.',
            confirmButtonText: 'Đã hiểu'
        });
        return false;
    }

    return true;
}


function formatStatus(statusid){
    switch (String(statusid)) {
        case '0': return 'Đã khóa tài khoản';
        case '1': return 'Còn hoạt động';
        default:  return 'Không xác định';
    }
}

// Icon khóa tài khoản hoặc mở tài khoảnkhoản
function getStatusIcon(statusid){
    switch (String(statusid)) {
        case '0': return '<i class="fa-solid fa-lock"></i>';
        case '1': return '<i class="fa-solid fa-lock-open"></i>';
        default:  return 'Không xác định';
    }
}
const allType = []
const allPermission = []

getAllType().then(types => {
    types.forEach(type => {
        allType[type.MaLoai] = type.TenLoai;
    });
})
getAllPermission().then(permissions => {
    permissions.forEach(permission =>{
        allPermission[permission.MaQuyen] = permission.TenQuyen;
    })
})

// Lấy tất cả loại người dùng
async function getAllType() {
    const res = await fetch("../../admin/API/index.php?type=getAllType");
    if (!res.ok) {
        const errorText = await res.text(); // đọc lỗi chi tiết
        console.error("Chi tiết lỗi:", errorText);
        throw new Error("Lỗi khi lấy tất cả loại");
    }
    const data = await res.json();
    // console.log(data)
    return data;
}

async function getTypeById(id){
    const res = await fetch(`../../admin/API/index.php?type=getTypeById&id=${id}`);
    if (!res.ok){
        const errorText = await res.text(); // đọc lỗi chi tiết
        console.error("Chi tiết lỗi:", errorText);
        throw new Error("Lỗi khi lấy loại");
    }
    const data = await res.json();
    // console.log(data)
    return data;
}
    

// Hàm loadUser chính: lấy tổng, sau đó lấy list, rồi render
async function loadUser(limit, offset, search) {
    try {
        searchName = search;  // lưu lại từ khóa
        // 1) Lấy tổng user
        const totalRes = await fetch(`../../admin/API/index.php?type=getTotalUser&search=${encodeURIComponent(searchName)}`);
        if (!totalRes.ok) throw new Error("Lỗi khi lấy tổng user");
        const totalData = await totalRes.json();
        totalUsers = totalData.total;

        // 2) Lấy danh sách user
        const listRes = await fetch(`../../admin/API/index.php?type=getUser&limit=${limit}&offset=${offset}&search=${encodeURIComponent(searchName)}`);
        if (!listRes.ok) throw new Error("Lỗi khi lấy danh sách user");
        const users = await listRes.json();

        // console.log("Users:", users);
        RenderUserList(users);

        // 3) Render phân trang
        const currentPage = Math.floor(offset / limit) + 1;
        renderUserPagination(totalUsers, currentPage);
    } catch (error) {
        console.error("Lỗi khi load user:", error);
    }
}

// Hàm render bảng user
async function RenderUserList(userlist) {
    console.log(allType)
    console.log(allPermission)
    const userTable = document.getElementById('table-user');
    let table = `<thead>
                    <tr>
                        <th>Mã Người Dùng</th>
                        <th>Tên Tài Khoản</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                        <th>Chức vụ</th>
                        <th>Quyền</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                 </thead>
                 <tbody>`;
    // const permissions = await Promise.all(
    //     userlist.map(user => getPermissionById(user.MaQuyen))
    // );
    // const types = await Promise.all(
    //     userlist.map(user => getTypeById(user.MaLoai))
    // );
    userlist.forEach((user) => {
        // const permission = permissions[index];
        // const type = types[index];
        table += `<tr id="user-row-${user.MaNguoiDung}">
                    <td>${user.MaNguoiDung}</td>
                    <td>${user.TenTK}</td>
                    <td>${user.DiaChi}</td>
                    <td>${user.Email}</td>
                    <td>${allType[user.MaLoai]}</td>
                    <td>${allPermission[user.MaQuyen]}</td>
                    <td>${user.NgayTaoTK}</td>
                    <td>${formatStatus(user.TrangThai)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-2" onclick="openEditUser(${user.MaNguoiDung})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="lockUnLockUser(${user.MaNguoiDung}, ${user.TrangThai})">
                            ${getStatusIcon(user.TrangThai)}
                        </button>
                    </td>
                </tr>`;
    });

    table += `</tbody>`;
    userTable.innerHTML = table;
}

// Hàm render phân trang
function renderUserPagination(totalItems, currentPage) {
    const pagination = document.getElementById('user-pagination');
    const totalPages = Math.ceil(totalItems / limit);

    let html = `<nav><ul class="pagination justify-content-center">`;

   
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
    } else if(currentPage +4 > totalPages){
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
    html += `</ul></nav>`;
    pagination.innerHTML = html;

    // Gắn sự kiện cho từng link
    pagination.querySelectorAll('a.page-link').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const page = a.dataset.page;
            let newOffset, newPage;
            if (page === 'prev') {
                newPage = currentPage - 1;
            } else if (page === 'next') {
                newPage = currentPage + 1;
            } else {
                newPage = parseInt(page);
            }
            newOffset = (newPage - 1) * limit;
            loadUser(limit, newOffset, searchName);
        });
    });
}
//add user
// tham chiếu đến modal và nút đóng
const addUserModal  = document.getElementById('addUserModal');
const addUserClose  = document.getElementById('addUserClose');
const addUserForm   = document.getElementById('addUserForm');

// Hàm mở modal
async function openAddUserModal() {
    if(!actionPermissions.canAdd){
           Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không có quyền hạn truy cập chức năng này!',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
    }

  const types = await getAllType();
    const selectType = document.getElementById('addUserType');
    selectType.innerHTML = '';
    types.forEach(type => {
        const option = document.createElement("option");
        option.value = type.MaLoai;
        option.textContent = type.TenLoai;
        // if (id && id == type.MaLoai) {
        //     option.selected = true;
        // }
        if(type.MaLoai != 3){
            selectType.appendChild(option)
        }
    })

    listPermission = await getAllPermission();
    const selectPermission = document.getElementById('permission');
    selectPermission.innerHTML = '';
    listPermission.forEach(permission => {
        const option = document.createElement("option");
        option.value = permission.MaQuyen;
        option.textContent = permission.TenQuyen;
        // if (id && id == permission.MaQuyen){
        //     option.selected = true;
        // }
        if(permission.MaQuyen != 1){
            selectPermission.appendChild(option);
        }
    });

    addUserModal.style.display = 'block';

}

// Khi bấm nút ×, hỏi xác nhận rồi mới đóng
addUserClose.onclick = () => {
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
            addUserModal.style.display = 'none';
            addUserForm.reset();
        }
    })
};

// Khi click ra ngoài modal-content, hỏi xác nhận rồi mới đóng
window.addEventListener('click', e => {
  if (e.target === addUserModal) {
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
            addUserModal.style.display = 'none';
            addUserForm.reset();
        }
    })
  }
});

// Xử lý submit form thêm user (ví dụ dùng fetch POST)
addUserForm.addEventListener('submit', async e => {
  e.preventDefault();
    const isValid = await validateAddUserForm(); // ❗ Đợi kết quả async
  if (!isValid) return;
  const formData = new FormData(addUserForm);
  const data = Object.fromEntries(formData.entries());
//   console.log(data)

  try {
    const res = await fetch('../../admin/API/index.php?type=addUser', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const json = await res.json();
    if (json.success) {
        Swal.fire({
            icon: 'success',
            title: 'Thông báo!',
            text: 'Thêm thành công',
            confirmButtonText: 'Đã hiểu'
          }); 
      addUserModal.style.display = 'none';
      addUserForm.reset();
      loadUser(limit, 0, searchName);``
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Cảnh báo!',
            text: 'Thêm thất bại',
            confirmButtonText: 'Đã hiểu'
          });     
        }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối.');
  }
});
// lấy quyền theo id
function getPermissionById(id) {
    return fetch(`../../admin/API/index.php?type=getPermissionById&id=${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Lỗi khi lấy dữ liệu');
            return response.json();   // ❗ phải return JSON Promise
        })
        .then(json => {
            return json;              // ❗ trả về object cho caller
        });
}

// lấy tất cả quyền
function getAllPermission(){
    return  fetch(`../../admin/API/index.php?type=getAllPermission`)
        .then(response => {
            if(!response.ok){
                throw new Error ('Lỗi khi lấy dữ liệu')
            }
            return response.json()
        })
        .then(permissions => {
            return permissions
        })
}
// truyền quyền vào select box
function renderEditPermission(listPermission, id){
    const selectPermission = document.getElementById('editMaQuyen');
    selectPermission.innerHTML = '';
    listPermission.forEach(permission => {
        const option = document.createElement("option");
        option.value = permission.MaQuyen;
        option.textContent = permission.TenQuyen;
        if (id && id == permission.MaQuyen){
            option.selected = true;
        }
        if(permission.MaQuyen != 1){
            selectPermission.appendChild(option);
        }
    });
}

// truyền loại người dùng vào select box 
async function renderEditType(id){
    const types = await getAllType();
    const selectType = document.getElementById('editMaLoai');
    selectType.innerHTML = '';
    types.forEach(type => {
        const option = document.createElement("option");
        option.value = type.MaLoai;
        option.textContent = type.TenLoai;
        if (id && id == type.MaLoai) {
            option.selected = true;
        }
        if(type.MaLoai != 3){
            selectType.appendChild(option)
        }
    })
}
// Biến cho edit 
const editModalEl = document.getElementById('editUserModal');
const editModal = new bootstrap.Modal(editModalEl);
// Hàm edit/delete placeholder
async function openEditUser(id) {
    if(!actionPermissions.canEdit){
           Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không có quyền hạn truy cập chức năng này!',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
    }
    try {
      const response = await fetch(`../../admin/API/index.php?type=getUserById&id=${id}`);
      if (!response.ok) throw new Error('Lỗi khi lấy dữ liệu');
      
      const user = await response.json();
      if ((await getPermissionById(user.MaQuyen)).TenQuyen == "Admin"){
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không thể sửa thông tin của tài khoản Admin.',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
      }
        //   console.log(getAllPermission());
        //   console.log(permission)
        //   getAllPermission();
      const permissionSelection = await getAllPermission();
      await renderEditPermission(permissionSelection, user.MaQuyen);
      await renderEditType(user.MaLoai);

      document.getElementById('editUserId').value  = user.MaNguoiDung;
      document.getElementById('editTenTK').value   = user.TenTK;
      document.getElementById('editMatKhau').value = '';
      document.getElementById('editEmail').value   = user.Email;
      document.getElementById('editDiaChi').value  = user.DiaChi;
      document.getElementById('editMaLoai').value  = user.MaLoai;
    //   document.getElementById('editMaQuyen').value = user.MaQuyen;
      editModal.show();
  
    } catch (error) {
      console.error(error);
    }
  }
async function updateUser(e){
    e.preventDefault();
    const isValid = await validateEditUserForm(); // ❗ Đợi kết quả async
    if (!isValid) return;
    // if(!validateEditUserForm()){
    //     return;
    // }
    // console.log('click')
    const formData = new FormData(editUserForm);
    const data = Object.fromEntries(formData.entries());
    const emailRegex = /^[^\s@]+@[^\s@]+\.com$/;

    if (!emailRegex.test(data.Email)) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo!',
            text: 'Bạn đã nhập sai định dạng email',
            confirmButtonText: 'Đã hiểu'
          });
        return
        } 
    try {        
        const res = await fetch('../../admin/API/index.php?type=updateUser', {
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
                text: 'Sửa thành công',
                confirmButtonText: 'Đã hiểu'
              });         
            editModal.hide();
            loadUser(limit, 0, searchName);
        } 
        else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Sửa thất bại',
                confirmButtonText: 'Đã hiểu'
              });        
        }
      } catch (err) {
        console.error(err);
        alert('Lỗi kết nối.');
      }   
}

// Khóa và mở khóa user
async function lockUnLockUser(id, status) {
     if(!actionPermissions.canDelete){
           Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không có quyền hạn truy cập chức năng này!',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
    }
    const newStatus = status === 1 ? 0 : 1;

    const response = await fetch(`../../admin/API/index.php?type=getUserById&id=${id}`);
      if (!response.ok) throw new Error('Lỗi khi lấy dữ liệu');
      
      const user = await response.json();
      if ((await getPermissionById(user.MaQuyen)).TenQuyen == "Admin"){
        Swal.fire({
            icon: 'error',
            title: 'Không được phép!',
            text: 'Bạn không thể sửa thông tin của tài khoản Admin.',
            confirmButtonText: 'Đã hiểu'
          });          
        return 
      }
    // console.log(user.MaQuyen)
    fetch(`../../admin/API/index.php?type=lockUser&id=${id}&trangthai=${newStatus}`)
        .then(response => {
            if (!response.ok) throw new Error("Lỗi khi khóa tài khoản");
            return response.json();
        })
        .then(data => {
            // Cập nhật trạng thái và icon ngay tại dòng đó
            const row = document.querySelector(`#user-row-${id}`);
            if (row) {
                // Cập nhật cột Trạng thái (index 7) và nút (index 8)
                const statusCell = row.children[7];
                const actionCell = row.children[8];

                statusCell.textContent = formatStatus(newStatus);
                actionCell.innerHTML = `
                    <button class="btn btn-sm btn-outline-success me-2" onclick="openEditUser(${id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="lockUnLockUser(${id}, ${newStatus})">
                        ${getStatusIcon(newStatus)}
                    </button>`;
            }

            // Hiển thị thông báo
            Swal.fire({
                icon: 'success',
                title: 'Thông báo!',
                text: newStatus === 0 ? 'Đã khóa tài khoản user' : 'Đã mở khóa tài khoản user',
                confirmButtonText: 'Đã hiểu'
            });
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Không thể thay đổi trạng thái.',
                confirmButtonText: 'Đã hiểu'
            });
        });
}
function searchUser(){
    let key = document.getElementById('searchInput').value;
    loadUser(limit,0,key)
}

// Khi trang load xong, khởi tạo
document.addEventListener('DOMContentLoaded', () => {
    loadUser(limit, 0, "");
        const permissionElement = document.getElementById('user-permissions');
        const actionPermissions = {
        canView: permissionElement.dataset.canView ,
        canEdit: permissionElement.dataset.canEdit ,
        canDelete: permissionElement.dataset.canDelete ,
        canAdd: permissionElement.dataset.canAdd
    };

    console.log("Can Delete:", actionPermissions.canDelete);

});
