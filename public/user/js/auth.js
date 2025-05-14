document.getElementById('login-form').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    const res = await fetch ('../../admin/API/index.php?type=login', {
        method : 'POST',
        headers : {
            'Content-Type' : 'application/json'
        },
        body: JSON.stringify({email, password}),
        credentials: 'include' 
    });
    // if (!res.ok) throw new Error ('Lỗi khi login');
    const user = await res.json();

    if(user){
        window.location.href = '../../admin/View/statistic.php'
    }
    else{
        Swal.fire({
      icon: 'warning',
      title: 'Thông báo!',
      text: 'Tài khoản hoặc mật khẩu không đúng',
      confirmButtonText: 'Đã hiểu'
    });  
    return;
    }
})