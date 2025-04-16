<?php 
require_once('../Model/product_Model.php');
require_once('../../config/connect.php');
class product_Controller{
    
    private $product_model = null;
    public function __construct(){
        $this->product_model = new product_Model();
    }

    public function getAllProducts(){
       return $this->product_model->getAllProducts();
    }
    public function AddProducts($MaKM,$MaDM,$tenSP,$Mota,$GioiTinh){
        return $this->product_model->AddProducts($MaKM,$MaDM,$tenSP,$Mota,$GioiTinh);
    }
    public function addProductImage($maSP,$Url){
        return $this->product_model->addProductImage($maSP,$Url);
    }
    public function getProductById($masp){
        return $this->product_model->getProductById($masp);
    }
    public function updateProduct($MaSP, $productData, $deletedImages = [], $newImages = []) {
        $updateResult = $this->product_model->updateProductInfo(
            $MaSP,
            $productData['TenSP'],
            $productData['MaDM'],
            $productData['MaKM'],
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
    public function deleteProduct($MaSP) {
        return $this->product_model->deleteProduct($MaSP);
    }
    public function searchByIdOrTenSP($keyword) {
        return $this->product_model->searchByIdOrTenSP($keyword);
    }
}

?>
