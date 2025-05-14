document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    const errorDiv = document.getElementById("error");

    form.addEventListener("submit", function (e) {
        const fullname = document.getElementById("fullname").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        let errorMsg = "";

        if (fullname === "" || email === "" || password === "" || confirmPassword === "") {
            errorMsg = "Vui lòng điền đầy đủ thông tin.";
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errorMsg = "Email không hợp lệ.";
        } else if (password.length < 6) {
            errorMsg = "Mật khẩu phải có ít nhất 6 ký tự.";
        } else if (password !== confirmPassword) {
            errorMsg = "Mật khẩu nhập lại không khớp.";
        }

        if (errorMsg !== "") {
            e.preventDefault();
            errorDiv.textContent = errorMsg;
        }
    });
});
