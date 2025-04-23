<?php
require_once("../../config/connect.php");
require_once("../../admin/Model/goodsReceipt_Model.php");
class goodsReceipt_Controller{
    private $goodsReceiptModel=null;
    public function __construct()
    {
        return $this->goodsReceiptModel=new goodsReceipt_Model();
    }
    public function getAllGoodsReceipt(){
        return $this->goodsReceiptModel->getAllGoodsReceipt();
    }
    public function getAllGoodsReceiptDetail($MaPN){
        return $this->goodsReceiptModel->getAllGoodsReceiptDetail($MaPN);
    }
    public function getAllTenSP(){
        return $this->goodsReceiptModel->getAllTenSP();
    }
    public function getAllSize(){
        return $this->goodsReceiptModel->getAllSize();
    }
    public function getAllProvider(){
        return $this->goodsReceiptModel->getAllProvider();
    }
    // Hàm chính xử lý thêm phiếu nhập + chi tiết
public function addGoodReceipt($data)
{
    try {
        if (empty($data['MaNCC']) || empty($data['products']) || !isset($data['TongTien']) || !isset($data['ProfitPercentage'])) {
            throw new Exception("Thiếu dữ liệu đầu vào!");
        }

        $MaNV = $_SESSION['user']['MaNV'] ?? 1;
        if (!$MaNV) throw new Exception("Không xác định nhân viên");

        // 1. Thêm phiếu nhập chính
        $MaPN = $this->goodsReceiptModel->addGoodReceiptRow($MaNV, $data['MaNCC'], $data['TongTien']);
        if (!$MaPN) throw new Exception("Không thể thêm phiếu nhập");

        $ProfitPercentage = $data['ProfitPercentage'];

        // 2. Duyệt products
        foreach ($data['products'] as $productId => $productData) {
            if (empty($productData['items'])) continue;

            // Tìm giá nhập cao nhất
            $maxImportPrice = max(array_map(function($item){
                return floatval($item['DonGia'] ?? 0);
            }, $productData['items']));

            // Tính giá bán mới
            $discount = $this->goodsReceiptModel->getProductDiscount($productId);
            $GiaBan = $maxImportPrice * (1 + ($ProfitPercentage/100) - ($discount/100));

            foreach ($productData['items'] as $item) {
                if (empty($item['MaSize']) || empty($item['DonGia']) || empty($item['SoLuongNhap'])) continue;

                $ThanhTien = $item['DonGia'] * $item['SoLuongNhap'];
                // Update tồn kho
                $this->goodsReceiptModel->updateProductQuantity(
                    $productId,
                    $item['MaSize'],
                    $item['SoLuongNhap']
                );
                // Thêm chi tiết
                $this->goodsReceiptModel->addGoodReceiptDetailRow(
                    $MaPN,
                    $productId,
                    $item['MaSize'],
                    $item['DonGia'],
                    $item['SoLuongNhap'],
                    $ThanhTien
                );

                
            }

            // Update giá bán tổng
            $this->goodsReceiptModel->updatePriceandAmount($productId, $GiaBan);
        }

        return json_encode(['success' => true, 'message' => 'Thêm phiếu nhập thành công', 'MaPN' => $MaPN]);

    } catch (Exception $e) {
        return json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

    private function calculateSalePrice($importPrice, $profitPercentage, $productId) {
        $discount = $this->goodsReceiptModel->getProductDiscount($productId);
        return $importPrice * (1 + ($profitPercentage/100) - ($discount/100));
    }

    public function getProductDiscount($MaSP) {
        return $this->goodsReceiptModel->getProductDiscount($MaSP);
    }
}