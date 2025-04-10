<?php 
require_once('../Model/statistic_Model.php');
require_once('../../config/connect.php');
class statistic_Controller{
    
    private $statistic_model = null;
    public function __construct(){
        $this->statistic_model = new statistic_model();
    }

    public function getInvoices(){
        return $this->statistic_model->invoices_statistic();
    }

    public function getTopProducts(){
        return $this->statistic_model->getTopProducts();
    }

    public function monthly_revenue($date){
        return $this->statistic_model->monthly_revenue($date);
    }
}

?>
