document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("order_history-form");

    form.querySelectorAll(".btn-view-detail").forEach(btn => {
        btn.addEventListener("click", function () {
            const MaHD = this.getAttribute("data-id");
            window.location.href = "order_history.php?detail=" + MaHD;
        });
    });

    const backBtn = document.getElementById("btn-back");
    if (backBtn) {
        backBtn.addEventListener("click", () => {
            window.location.href = "order_history.php";
        });
    }

    form.querySelectorAll(".btn-cancel-order").forEach(btn => {
        btn.addEventListener("click", function () {
            const maHD = this.getAttribute("data-id");
            showConfirmDialog("Bạn có chắc muốn hủy đơn này?", function () {
                fetch('../Ajax/cancel_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'MaHD=' + maHD
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    location.reload();
                })
                .catch(() => alert("Có lỗi xảy ra."));
            });
        });
    });

    function showConfirmDialog(message, onConfirm) {
        const overlay = document.createElement("div");
        overlay.className = "confirm-dialog-overlay";
        const dialog = document.createElement("div");
        dialog.className = "confirm-dialog";
        dialog.innerHTML = `
            <p>${message}</p>
            <button class="btn btn-danger">Có</button>
            <button class="btn btn-secondary">Không</button>
        `;
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);

        dialog.querySelector(".btn-danger").addEventListener("click", () => {
            document.body.removeChild(overlay);
            onConfirm();
        });
        dialog.querySelector(".btn-secondary").addEventListener("click", () => {
            document.body.removeChild(overlay);
        });
    }
});
