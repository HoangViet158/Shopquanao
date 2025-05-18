<?php
require_once(__DIR__ . '/../Model/product_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
class product_Controller
{

    private $product_model = null;
    public function __construct()
    {
        $this->product_model = new product_Model();
    }

    public function getAllProducts($page = 1, $perPage = 10)
    {
        // Đảm bảo $page và $perPage là số nguyên dương
        $page = max(1, (int)$page);
        $perPage = max(1, min(100, (int)$perPage)); // Giới hạn tối đa 100 sản phẩm/trang

        return $this->product_model->getAllProducts($page, $perPage);
    }
    public function AddProducts($MaKM, $MaDM, $MaPL, $tenSP, $Mota, $GioiTinh)
    {
        return $this->product_model->AddProducts($MaKM, $MaDM, $MaPL, $tenSP, $Mota, $GioiTinh);
    }
    public function addProductImage($maSP, $Url)
    {
        return $this->product_model->addProductImage($maSP, $Url);
    }
    public function getProductById($masp)
    {
        return $this->product_model->getProductById($masp);
    }
    public function updateProduct($MaSP, $productData, $deletedImages = [], $newImages = [])
    {
        $updateResult = $this->product_model->updateProductInfo(
            $MaSP,
            $productData['TenSP'],
            $productData['MaDM'],
            $productData['MaPL'],
            $productData['GioiTinh'],
            $productData['MoTa']
        );

        if (!$updateResult) {
            return ['success' => false, 'message' => 'Cập nhật thông tin sản phẩm thất bại'];
        }
        if (!empty($deletedImages)) {
            foreach ($deletedImages as $imageId) {
                $this->product_model->deleteProductImage($imageId);
            }
        }
        if (!empty($newImages)) {
            foreach ($newImages as $imagePath) {
                if (!$MaSP) {
                    return ['success' => false, 'message' => 'Mã sản phẩm không hợp lệ khi thêm ảnh mới'];
                }
                $this->product_model->addProductImage($MaSP, $imagePath);
            }
        }

        return ['success' => true, 'message' => 'Cập nhật sản phẩm thành công'];
    }
    public function deleteProduct($MaSP)
    {
        if ($this->product_model->checkAvailableProduct($MaSP)) {
            return [
                'success' => false,
                'message' => 'Không thể xóa sản phẩm này vì số lượng tồn kho lớn hơn 0'
            ];
        }

        $result = $this->product_model->deleteProduct($MaSP);

        return $result
            ? ['success' => true, 'message' => 'Xóa sản phẩm thành công']
            : ['success' => false, 'message' => 'Xóa sản phẩm thất bại'];
    }
    public function searchByIdOrTenSP($keyword)
    {
        return $this->product_model->searchByIdOrTenSP($keyword);
    }
    public function getProductsByType($type)
    {
        return $this->product_model->getProductsByType($type);
    }
    public function getProductImages($masp)
    {
        return $this->product_model->getProductImages($masp);
    }
}
