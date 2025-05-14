document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", function (e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        if (email === "" || password === "") {
            alert("Vui lòng nhập đầy đủ email và mật khẩu.");
            e.preventDefault();
        } else if (!email.includes("@")) {
            alert("Email không hợp lệ.");
            e.preventDefault();
        }
    });
});
