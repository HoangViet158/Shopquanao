<?php 
require_once(__DIR__ . '/../Model/statistic_Model.php');
require_once(__DIR__ . '/../../config/connect.php');
// header('Content-Type: application/json');
class statistic_Controller{
    
    private $statistic_model = null;
    public function __construct(){
        $this->statistic_model = new statistic_model();
    }

    public function getInvoices($limit, $offset, $daystart, $dayend, $id){
        return $this->statistic_model->invoices_statistic($limit, $offset, $daystart, $dayend, $id);
    }

    public function getTopProducts(){
        return $this->statistic_model->getTopProducts();
    }

    public function getTopUserCost($dateStart, $dateEnd){
        return $this->statistic_model->loadTop5User($dateStart, $dateEnd);
    }

    public function monthly_revenue($date){
        return $this->statistic_model->monthly_revenue($date);
    }
    
    public function total_invoices($daystart,$dayend,$id){
        return $this->statistic_model->total_invoices_statistic($daystart,$dayend,$id);
    }
    public function handleRequest(){
        if (isset($_GET['date'])){
            $date = $_GET['date'];
            $result = $this->monthly_revenue($date);
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
    }
}
$controller = new statistic_Controller();
$controller->handleRequest(); 
?>
