<?php require_once "sidebar.php"  ?>
<div id="statistic-content" class="content-container">
    <div class="Mange_client" >
        <h3> Chi tiết đơn hàng </h3>
        <div id="table-container"  class="table-responsive">
            <h4 class="text-center"> Danh sách hóa đơn </h4>
            <table id="table-invoices" class="table table-custom table-striped table-hover">
            </table>
            <nav aria-label="Page navigation" class="mt-4">
              <ul id="invoice-detail-pagination" class="pagination justify-content-center">
                <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
              </ul>
            </nav>
        </div>
        <div id="invoiceModal" class="modal">
            <div class="modal-content"  style= "max-width: 800px">
                <span class="close">&times;</span>
                <h2 class="modal-header">Chi tiết hóa đơn</h2>
                <table id="invoiceDetails"  class="table table-custom table-striped table-hover" ></table>
            </div>
        </div>
    </div>
</body>
<script src="../../public/admin/js/invoice-detail.js"></script>
