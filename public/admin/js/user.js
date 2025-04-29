// Định nghĩa biến toàn cục
let limit = 5;
let totalUsers = 0;
let searchName = "";

// Format trạng thái user
function formatStatus(statusid){
    switch (String(statusid)) {
        case '0': return 'Đã khóa tài khoản';
        case '1': return 'Còn hoạt động';
        default:  return 'Không xác định';
    }
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

        console.log("Users:", users);
        RenderUserList(users);

        // 3) Render phân trang
        const currentPage = Math.floor(offset / limit) + 1;
        renderUserPagination(totalUsers, currentPage);
    } catch (error) {
        console.error("Lỗi khi load user:", error);
    }
}

// Hàm render bảng user
function RenderUserList(userlist) {
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

    userlist.forEach(user => {
        table += `<tr>
                    <td>${user.MaNguoiDung}</td>
                    <td>${user.TenTK}</td>
                    <td>${user.DiaChi}</td>
                    <td>${user.Email}</td>
                    <td>${user.MaLoai}</td>
                    <td>${user.MaQuyen    }</td>
                    <td>${user.NgayTaoTK}</td>
                    <td>${formatStatus(user.TrangThai)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-2" onclick="editUser(${user.MaNguoiDung})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.MaNguoiDung})">
                            <i class="fas fa-trash"></i>
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
function openAddUserModal() {
  addUserModal.style.display = 'block';
}

// Khi bấm nút ×, hỏi xác nhận rồi mới đóng
addUserClose.onclick = () => {
  if (confirm('Bạn có chắc muốn hủy? Mọi thông tin vừa nhập sẽ mất.')) {
    addUserModal.style.display = 'none';
    addUserForm.reset();
  }
};

// Khi click ra ngoài modal-content, hỏi xác nhận rồi mới đóng
window.addEventListener('click', e => {
  if (e.target === addUserModal) {
    if (confirm('Bạn có chắc muốn hủy? Mọi thông tin vừa nhập sẽ mất.')) {
      addUserModal.style.display = 'none';
      addUserForm.reset();
    }
  }
});

// Xử lý submit form thêm user (ví dụ dùng fetch POST)
addUserForm.addEventListener('submit', async e => {
  e.preventDefault();
  const formData = new FormData(addUserForm);
  const data = Object.fromEntries(formData.entries());
  console.log(data)

  try {
    const res = await fetch('../../admin/API/index.php?type=addUser', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const json = await res.json();
    if (json.success) {
      alert('Thêm user thành công!');
      addUserModal.style.display = 'none';
      addUserForm.reset();
      loadUser(limit, 0, searchName);
    } else {
      alert('Thêm thất bại, thử lại.');
    }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối.');
  }
});


// Hàm edit/delete placeholder
function editUser(id)   { 
    fetch (`../../admin/API/index.php?type=getUserById&id=${id}`)
        .then(response =>{
            if(!response.ok){
                throw new Error('Lỗi khi lấy dữ liệu')
            }
            return response.json()
        })
        .then(user => {
            console.log(user);
            document.getElementById('editUserId').value  = user.MaNguoiDung;
            document.getElementById('editTenTK').value   = user.TenTK;
            document.getElementById('editMatKhau').value = '';   // mặc định trống
            document.getElementById('editEmail').value   = user.Email;
            document.getElementById('editDiaChi').value  = user.DiaChi;
            document.getElementById('editMaLoai').value  = user.MaLoai;
            document.getElementById('editMaQuyen').value = user.MaQuyen;
            editModal.show();
        })
        .catch(error => {
            console.error(error);
        })
}
function deleteUser(id) { console.log("Delete user", id); }

// Khi trang load xong, khởi tạo
document.addEventListener('DOMContentLoaded', () => {
    loadUser(limit, 0, "");
});
