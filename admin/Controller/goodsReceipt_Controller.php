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
            if (
                empty($data['MaNCC']) || empty($data['products']) ||
                !isset($data['TongTien']) || !isset($data['ProfitPercentage'])
            ) {
                throw new Exception("Vui lòng nhập đầy đủ thông tin!");
            }

            session_start();
            // if (!isset($_SESSION['user'])) {
            //     throw new Exception("Bạn chưa đăng nhập!");
            // }
            $maNV = $_SESSION['user']['id'] ?? null;
            if (!$maNV) {
                throw new Exception("Không xác định được nhân viên!");
            }

            $profitPercentage = $data['ProfitPercentage'];
            $errors = [];
            foreach ($data['products'] as $productId => $product) {
                if (empty($product['items'])) continue;

                // Tính tổng giá trị nhập
                $totalValue = 0;
                $totalQty = 0;
                foreach ($product['items'] as $item) {
                    $totalValue += $item['DonGia'] * $item['SoLuongNhap'];
                    $totalQty += $item['SoLuongNhap'];
                }

                // Tính giá trung bình
                $avgPrice = $totalQty > 0 ? $totalValue / $totalQty : 0;

                // Lấy giá vốn hiện tại
                $currentAvgCost = $this->goodsReceiptModel->getAverageCostPrice($productId);

                // Tính giá vốn mới sau khi nhập
                if ($currentAvgCost == 0) {
                    $newAvgCost = $avgPrice;
                } else {
                    $currentQty = $this->goodsReceiptModel->getCurrentQuantity($productId);
                    $newAvgCost = ($currentAvgCost * $currentQty + $totalValue) / ($currentQty + $totalQty);
                }
                // ct tính giá nhập trung bình= giá nhập tb hiện tại x số lượng tồn + giá nhập mới x số lượng mới / số lượng tồn + số lượng mới
                // Tính giá bán đề xuất
                $discount = $this->goodsReceiptModel->getProductDiscount($productId) ?? 0;
                $suggestedPrice = $newAvgCost * (1 + ($profitPercentage / 100) - ($discount / 100));

                // Kiểm tra giá đề xuất
                if ($suggestedPrice < $newAvgCost) {
                    $errors[] = "Sản phẩm {$product['productName']} có giá bán đề xuất thấp hơn giá nhập";
                }
            }

            if (!empty($errors)) {
                throw new Exception(implode("<br>", $errors));
            }
            $maPN = $this->goodsReceiptModel->addGoodReceiptRow($maNV, $data['MaNCC'], $data['TongTien']);
            if (!$maPN) {
                throw new Exception("Lỗi khi tạo phiếu nhập!");
            }
            foreach ($data['products'] as $productId => $product) {
                if (empty($product['items'])) continue;

                // Lưu từng sản phẩm
                foreach ($product['items'] as $item) {
                    $thanhTien = $item['DonGia'] * $item['SoLuongNhap'];
                    $this->goodsReceiptModel->updateProductQuantity(
                        $productId,
                        $item['MaSize'],
                        $item['SoLuongNhap']
                    );
                    $this->goodsReceiptModel->addGoodReceiptDetailRow(
                        $maPN,
                        $productId,
                        $item['MaSize'],
                        $item['DonGia'],
                        $item['SoLuongNhap'],
                        $thanhTien
                    );
                }

                $this->goodsReceiptModel->updatePriceandAmount($productId, $suggestedPrice);
            }

            return json_encode([
                'success' => true,
                'message' => 'Thêm phiếu nhập thành công!',
                'MaPN' => $maPN
            ]);
        } catch (Exception $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function calculateSuggestedPrices($data)
    {
        try {
            // Kiểm tra dữ liệu
            if (empty($data['products']) || !isset($data['ProfitPercentage'])) {
                throw new Exception("Vui lòng nhập đầy đủ thông tin!");
            }

            $results = [];
            $profitPercentage = $data['ProfitPercentage'];

            foreach ($data['products'] as $productId => $product) {
                if (empty($product['items'])) continue;

                // Tính tổng giá trị và số lượng
                $totalValue = 0;
                $totalQty = 0;
                foreach ($product['items'] as $item) {
                    $totalValue += $item['DonGia'] * $item['SoLuongNhap'];
                    $totalQty += $item['SoLuongNhap'];
                }
                $avgPrice = $totalQty > 0 ? $totalValue / $totalQty : 0;

                // Lấy giá vốn hiện tại
                $currentAvgCost = $this->goodsReceiptModel->getAverageCostPrice($productId);

                // Tính giá vốn mới
                if ($currentAvgCost == 0) {
                    $newAvgCost = $avgPrice;
                } else {
                    $currentQty = $this->goodsReceiptModel->getCurrentQuantity($productId);
                    $newAvgCost = ($currentAvgCost * $currentQty + $totalValue) / ($currentQty + $totalQty);
                }

                // Lấy % giảm giá
                $discount = $this->goodsReceiptModel->getProductDiscount($productId) ?? 0;

                // Tính giá bán đề xuất
                $suggestedPrice = $newAvgCost * (1 + ($profitPercentage / 100) - ($discount / 100));

                $results[] = [
                    'productId' => $productId,
                    'productName' => $product['productName'] ?? '',
                    'averagePrice' => $newAvgCost,
                    'discount' => $discount,
                    'suggestedPrice' => $suggestedPrice
                ];
            }

            return ['success' => true, 'data' => $results];
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
