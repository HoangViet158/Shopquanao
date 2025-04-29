<?php
require_once("../../config/connect.php");
class user_Model{
    private $database =null;
    public function __construct()
    {
        $this->database=new Database();
    }
    function getUserByUsername($username){
        $sql = "SELECT * FROM taikhoan WHERE TenTK = ?";
        
        $stmt = $this->database->prepare($sql);

        $stmt->bind_param("s",$username);

        $stmt->execute();

        return $stmt->get_result();
    }
}
?>