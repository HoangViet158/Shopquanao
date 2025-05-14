<?php 
require_once(__DIR__ . "/../../config/connect.php"); 
class permission_model {
    private $database = null;
    public function __construct(){
        $this->database = new Database();
    }
    public function getPermissionById($id){
        $sql = "SELECT TenQuyen
                FROM quyen
                WHERE MaQuyen = ? And TrangThai = 1";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function getAllPermission(){
        $sql = "SELECT * 
                FROM quyen
                WHERE TrangThai = 1";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();         
    }

    public function getAllType(){
        $sql = "SELECT * 
                FROM loai";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();   
    }

    public function getTypeById($id){
        $sql = "SELECT * 
                FROM loai
                WHERE MaLoai = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        return $stmt->get_result();   
    }

    public function addPermission($tenQuyen){
        $sql = "INSERT INTO quyen (TenQuyen, TrangThai) VALUES (?, 1)";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('s', $tenQuyen);
        $stmt->execute();
        if ($stmt->affected_rows > 0){
            return true;
        }
        return false;   
    }

    public function isPermissionDetailExist($MaQuyen, $MaCTQ){
        $sql = "SELECT COUNT(*) AS count FROM chitiet_quyen_cn WHERE MaQuyen = ? && MaCTQ = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param("ii", $MaQuyen, $MaCTQ);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function editPermissionDetail($MaQuyen, $MaCTQ, $action){
        if($MaQuyen == NULL || $MaCTQ == NULL){
            return;
        } 
        if($this->isPermissionDetailExist($MaQuyen, $MaCTQ)){
            $sql = "UPDATE chitiet_quyen_cn SET  HanhDong = ? WHERE MaQuyen = ? && MaCTQ = ?";
            $stmt = $this->database->connection()->prepare($sql);
            $stmt->bind_param('sii', $action, $MaQuyen, $MaCTQ);
            return $stmt->execute();
        }
        else{
            $sql = "INSERT INTO chitiet_quyen_cn (MaQuyen, MaCTQ, HanhDong) VALUE (?, ?, ?)";
            $stmt1 = $this->database->connection()->prepare($sql);
            $stmt1->bind_param('iis', $MaQuyen, $MaCTQ, $action);
            return $stmt1->execute();
        }
    }
    
    public function deletePermission($id){
        $conn = $this->database->connection();

        // Cập nhật người dùng có quyền này về quyền mặc định
        if($this->isPermissionInUse($id)){
            $updateSql = "UPDATE taikhoan SET MaQuyen = 3 WHERE MaQuyen = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("i", $id);
            $stmtUpdate->execute();
        }


        //Xóa chi tiết quyền có mã quyền đã xóa
        $deleteSql1 = "DELETE FROM chitiet_quyen_cn WHERE MaQuyen = ?";
        $stmtDelete1 = $conn->prepare($deleteSql1);
        $stmtDelete1->bind_param('i',$id);
        $stmtDelete1->execute();

        
        // Xóa quyền
        $deleteSql = "DELETE FROM quyen WHERE MaQuyen = ?";
        $stmtDelete = $conn->prepare($deleteSql);
        $stmtDelete->bind_param("i", $id);
        $success = $stmtDelete->execute();

        return $success;
    }

    public function isPermissionInUse($id) {
        $sql = "SELECT COUNT(*) AS count FROM taikhoan WHERE MaQuyen = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function getAllFunction(){
        $sql = "SELECT * FROM chucnang";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->execute();
        return $result = $stmt->get_result();
    }

    public function getAction($MaQuyen, $MaCTQ){
        $sql = "SELECT * FROM chitiet_quyen_cn WHERE MaQuyen = ? AND MaCTQ = ?";
        $stmt = $this->database->connection()->prepare($sql);
        $stmt->bind_param('ii', $MaQuyen, $MaCTQ);
        $stmt->execute();
        return $stmt->get_result();
    }

}