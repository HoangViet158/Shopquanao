//Đổi mật khẩu
document.getElementById('change-password-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const password = document.getElementById('password-new').value;
    const passwordConfirm = document.getElementById('password-new-confirm').value;

    if(password == passwordConfirm){
        const res = await fetch('../../admin/API/index.php?type=changePassword',{
            method : 'POST',
            headers : {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({password}),
            credentials: 'include' // đảm bảo session vẫn hoạt động
        })
        if (!res.ok) throw new Error('Lỗi khi đổi mật khẩu')
        const result =  await res.json();
        if(result.success == true){
            Swal.fire({
            icon: 'success',
            title: 'Thông báo!',
            text: 'Đổi mật khẩu thành công',
            confirmButtonText: 'Đã hiểu'
            });  
            document.getElementById('password-new').value = "";
            document.getElementById('password-new-confirm').value = "";
            return;
        }
        else{
            Swal.fire({
                icon: 'error',
                title: 'Thông báo!',
                text: 'Đổi mật khẩu thất bại',
                confirmButtonText: 'Đã hiểu'
                });  
            return;
        }
    }else{
        Swal.fire({
            icon: 'warning',
            title: 'Thông báo!',
            text: 'Mật khẩu mới và mật khẩu nhập lại không trùng khớp!',
            confirmButtonText: 'Đã hiểu'
            });  
        return;
    }
})