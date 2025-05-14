<?php
require_once(__DIR__ . '/../Model/products_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
class product_Controller
{

    private $product_model = null;
    public function __construct()
    {
        $this->product_model = new products_Model();
    }

    public function filterProducts($filter, $page = 1, $limit = 10)
    {
        // Đảm bảo $page và $limit là số nguyên dương
        $page = max(1, (int)$page);
        $limit = max(1, min(100, (int)$limit)); // Giới hạn tối đa 100 sản phẩm/trang

        return $this->product_model->filterProducts($filter, $page, $limit);
    }
    public function getProductDetail($id)
    {
        return $this->product_model->getProductById($id);
    }
}
