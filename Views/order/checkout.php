<h2>Thanh toán đơn hàng</h2>
<form action="/checkout/place" method="POST">
    <label><input type="radio" name="payment_method" value="cash" checked> Thanh toán tiền mặt</label><br>
    <label><input type="radio" name="payment_method" value="bank"> Chuyển khoản</label><br><br>
    <button type="submit">Xác nhận đặt hàng</button>
</form>
