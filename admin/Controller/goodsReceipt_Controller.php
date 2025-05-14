<?php
require_once(__DIR__ . "/../../config/connect.php");
require_once(__DIR__ . "/../../admin/Model/goodsReceipt_Model.php");
class goodsReceipt_Controller
{
    private $goodsReceiptModel = null;
    public function __construct()
    {
        return $this->goodsReceiptModel = new goodsReceipt_Model();
    }
    public function getAllGoodsReceipt()
    {
        return $this->goodsReceiptModel->getAllGoodsReceipt();
    }
    public function getAllGoodsReceiptDetail($MaPN)
    {
        return $this->goodsReceiptModel->getAllGoodsReceiptDetail($MaPN);
    }
    public function getAllTenSP()
    {
        return $this->goodsReceiptModel->getAllTenSP();
    }
    public function getAllSize()
    {
        return $this->goodsReceiptModel->getAllSize();
    }
    public function getAllProvider()
    {
        return $this->goodsReceiptModel->getAllProvider();
    }
    public function addGoodReceipt($data)
    {
        try {
            if (empty($data['MaNCC']) || empty($data['products']) || !isset($data['TongTien']) || !isset($data['ProfitPercentage'])) {
                throw new Exception("Missing input data!");
            }

            $MaNV = $_SESSION['user']['MaNV'] ?? 1;
            if (!$MaNV) throw new Exception("Cannot identify staff");

            $ProfitPercentage = $data['ProfitPercentage'];
            $errors = [];
            //validate trước khi thêm phiếu nhập
            foreach ($data['products'] as $productId => $productData) {
                if (empty($productData['items'])) continue;
                $totalImportValue = 0;
                $totalQuantity = 0;

                foreach ($productData['items'] as $item) {
                    $itemValue = $item['DonGia'] * $item['SoLuongNhap'];
                    $totalImportValue += $itemValue;
                    $totalQuantity += $item['SoLuongNhap'];
                }

                // giá tb đợt nhập hiện tại
                $currentAvgPrice = $totalQuantity > 0 ? $totalImportValue / $totalQuantity : 0;

                // Lấy giá vốn trung bình hiện tại từ hệ thống
                $averageCostPrice = $this->goodsReceiptModel->getAverageCostPrice($productId);

                // Giá mới = (Giá cũ * SL hiện tại + Giá trị nhập mới) / (SL hiện tại + SL mới nhập)
                if ($averageCostPrice == 0) {
                    $averageCostPrice = $currentAvgPrice;
                } else {
                    $currentTotalValue = $averageCostPrice * $this->goodsReceiptModel->getCurrentQuantity($productId);
                    $newAverageCostPrice = ($currentTotalValue + $totalImportValue) /
                        ($this->goodsReceiptModel->getCurrentQuantity($productId) + $totalQuantity);
                    $averageCostPrice = $newAverageCostPrice;
                }
                // tính giá bán đề xuất
                $proposedPrice = $this->calculateSalePrice($averageCostPrice, $ProfitPercentage, $productId);
                if ($proposedPrice < $averageCostPrice) {
                    $errors[] = "Product " . $productData['productName'] . " has suggested price " . number_format($proposedPrice) .
                        " lower than average import price " . number_format($averageCostPrice) .
                        ". Please adjust profit percentage or remove discount.";
                }
            }

            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }

            $MaPN = $this->goodsReceiptModel->addGoodReceiptRow($MaNV, $data['MaNCC'], $data['TongTien']);
            if (!$MaPN) throw new Exception("Cannot add receipt");

            foreach ($data['products'] as $productId => $productData) {
                if (empty($productData['items'])) continue;

                // tính giá tb cho đợt nhập này
                $totalValue = 0;
                $totalQty = 0;
                foreach ($productData['items'] as $item) {
                    $totalValue += $item['DonGia'] * $item['SoLuongNhap'];
                    $totalQty += $item['SoLuongNhap'];
                }
                $averagePrice = $totalValue / $totalQty;

                //tính giá bán
                $proposedPrice = $this->calculateSalePrice($averagePrice, $ProfitPercentage, $productId);

                foreach ($productData['items'] as $item) {
                    if (empty($item['MaSize']) || empty($item['DonGia']) || empty($item['SoLuongNhap'])) continue;

                    $ThanhTien = $item['DonGia'] * $item['SoLuongNhap'];

                    $this->goodsReceiptModel->updateProductQuantity(
                        $productId,
                        $item['MaSize'],
                        $item['SoLuongNhap']
                    );

                    $this->goodsReceiptModel->addGoodReceiptDetailRow(
                        $MaPN,
                        $productId,
                        $item['MaSize'],
                        $item['DonGia'],
                        $item['SoLuongNhap'],
                        $ThanhTien
                    );
                }
                $this->goodsReceiptModel->updatePriceandAmount($productId, $proposedPrice);
            }

            return json_encode([
                'success' => true,
                'message' => 'Receipt added successfully',
                'MaPN' => $MaPN
            ]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function calculateSalePrice($importPrice, $profitPercentage, $productId)
    {
        $discount = $this->goodsReceiptModel->getProductDiscount($productId);
        return $importPrice * (1 + ($profitPercentage / 100) - ($discount / 100));
    }
    public function calculateSuggestedPrices($data) {
    try {
        if (empty($data['products']) || !isset($data['ProfitPercentage'])) {
            throw new Exception("Thiếu dữ liệu đầu vào!");
        }

        $ProfitPercentage = $data['ProfitPercentage'];
        $result = [];

        foreach ($data['products'] as $productId => $productData) {
            if (empty($productData['items'])) continue;

            $totalImportValue = 0;
            $totalQuantity = 0;

            // Tính tổng giá trị và tổng số lượng nhập trong đợt này
            foreach ($productData['items'] as $item) {
                $totalImportValue += $item['DonGia'] * $item['SoLuongNhap'];
                $totalQuantity += $item['SoLuongNhap'];
            }
            $currentAvgPrice = $totalQuantity > 0 ? $totalImportValue / $totalQuantity : 0;

            // Lấy giá vốn trung bình hiện tại từ hệ thống
            $averageCostPrice = $this->goodsReceiptModel->getAverageCostPrice($productId);

            // Nếu chưa từng nhập, dùng giá trung bình đợt này
            if ($averageCostPrice == 0) {
                $averageCostPrice = $currentAvgPrice;
            } else {
                $currentQuantity = $this->goodsReceiptModel->getCurrentQuantity($productId);
                // Giá trị tồn kho hiện tại
                $currentTotalValue = $averageCostPrice * $currentQuantity;

                // Tính lại giá vốn trung bình mới sau khi nhập thêm
                $newAverageCostPrice = ($currentTotalValue + $totalImportValue) / ($currentQuantity + $totalQuantity);
                $averageCostPrice = $newAverageCostPrice;
            }

            // Lấy discount hiện tại
            $discount = $this->goodsReceiptModel->getProductDiscount($productId);
            $discount = is_numeric($discount) ? floatval($discount) : 0;

            // Tính giá bán đề xuất
            $suggestedPrice = $averageCostPrice * (1 + ($ProfitPercentage / 100) - ($discount / 100));
            $result[] = [
                'productId' => $productId,
                'productName' => $productData['productName'] ?? '',
                'averagePrice' => $averageCostPrice,
                'discount' => $discount,
                'suggestedPrice' => $suggestedPrice
            ];
        }

        return ['success' => true, 'data' => $result];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


    public function getProductDiscount($MaSP)
    {
        return $this->goodsReceiptModel->getProductDiscount($MaSP);
    }

    public function searchGoodsReceipt($keyword)
    {
        return $this->goodsReceiptModel->searchGoodsReceipt($keyword);
    }
}
