<?php
class Order {
    public function create($cart, $paymentMethod) {
        // Giả lập tạo đơn hàng, lưu vào DB ở thực tế
        // Trả về mã đơn hàng
        return rand(1000, 9999);
    }
}
