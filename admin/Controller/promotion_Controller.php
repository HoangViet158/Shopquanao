    <?php
    require_once(__DIR__ . "/../../config/connect.php");
    require_once(__DIR__ . "/../../admin/Model/promotion_Model.php");
    class promotion_Controller
    {
        private $promotion_model = null;
        public function __construct()
        {
            $this->promotion_model = new promotion_Model();
        }
        public function getAllPromotions()
        {
            return $this->promotion_model->getAllPromotions();
        }
        public function addAndApplyPromotion($name, $value, $startDate, $endDate, $productIds)
        {

            return $this->promotion_model->addAndApplyPromotion($name, $value, $startDate, $endDate, $productIds);
        }
        public function checkPromotionProfit($productId, $discountPercent)
        {
            try {
                $result = $this->promotion_model->checkPromotionProfit($productId, $discountPercent);
                return ['success' => true, 'data' => $result];
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
        public function getAllProducts()
        {
            $result = $this->promotion_model->getAllProducts();
            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            return ['success' => true, 'data' => $products];
        }
        public function updateAndApplyPromotion($promotionId, $name, $value, $startDate, $endDate, $status, $productIds)
        {
            try {
                $result = $this->promotion_model->updateAndApplyPromotion(
                    $promotionId,
                    $name,
                    $value,
                    $startDate,
                    $endDate,
                    $status,
                    $productIds
                );
                return $result;
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        public function getPromotionDetail($promotionId)
        {
            try {
                $promotion = $this->promotion_model->getPromotionById($promotionId);
                if (!$promotion) {
                    throw new Exception("Không tìm thấy khuyến mãi");
                }

                $products = $this->promotion_model->getProductsByPromotion($promotionId);

                return [
                    'success' => true,
                    'promotion' => $promotion,
                    'products' => $products
                ];
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        public function deletePromotion($promotionId)
        {
            try {
                $result = $this->promotion_model->deletePromotion($promotionId);
                return $result;
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }
