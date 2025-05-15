//Cập nhật thông tin user
document.getElementById('user-information-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const address = document.getElementById('address').value;

    if (username.trim() !== "" && address.trim() !== ""){
        const res = await fetch('../../admin/API/index.php?type=updateInformationUser',{
            method : 'POST',
            headers : {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({username, address}),
            credentials: 'include' // đảm bảo session vẫn hoạt động
        })
        if (!res.ok) throw new Error('Lỗi khi cập nhật thông tin')
        const result =  await res.json();
        if(result.success == true){
            Swal.fire({
            icon: 'success',
            title: 'Thông báo!',
            text: 'Cập nhật thông tin thành công',
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
                text: 'Cập nhật thông tin thất bại',
                confirmButtonText: 'Đã hiểu'
                });  
            return;
        }
    }else{
        Swal.fire({
            icon: 'warning',
            title: 'Thông báo!',
            text: 'Không được để trống tên tài khoản hoặc địa chỉ!',
            confirmButtonText: 'Đã hiểu'
            });  
        return;
    }
})