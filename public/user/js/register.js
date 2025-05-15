document.getElementById('register-form').addEventListener('submit', async function (e) {
    const gmail = document.getElementById('email').value;
  e.preventDefault();
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
            return;
        }
    } catch (error) {
        console.error('Lỗi khi kiểm tra email:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không thể kiểm tra email.',
            confirmButtonText: 'Đã hiểu'
        });
        return;
    }
  data = {
        'TenTK' : document.getElementById('username').value, 
        'MatKhau': document.getElementById('password').value,
        'Email'  :document.getElementById('email').value,
        'DiaChi': document.getElementById('address').value,
    }
  console.log(data)
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
            text: 'Tạo tài khoản thành công',
            confirmButtonText: 'Đã hiểu'
          }); 
          window.location.href = '../../user/View/login.php'
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Cảnh báo!',
            text: 'Tạo tài khoản thất bại',
            confirmButtonText: 'Đã hiểu'
          });     
        }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối.');
  }
});