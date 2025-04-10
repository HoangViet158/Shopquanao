<?php 
class Database{
    private $server_name = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "shopaoquan";

    private $conn = null;
    private $result = null;

    public function __construct(){
        $this->connection();
    }

    public function connection(){
        $this->conn = new mysqli($this->server_name, $this->username, $this->password, $this->dbname);
    
        if ($this->conn->connect_error){
            die("Kết nối thất bại: ". $this->conn->connect_error);
        } else {
            mysqli_set_charset($this->conn, 'utf8'); // Khắc phục lỗi tiếng Việt
        }
        return $this->conn;
    }
    

    public function disconnect(){
        $this->conn->close();
    }

    public function execute($sql){
        $this->result = $this->conn->query($sql);
        return $this->result;
    }

     // Phương thức prepare
     public function prepare($sql){
        // Kiểm tra xem kết nối có tồn tại không
        if ($this->conn == null) {
            die("Không có kết nối cơ sở dữ liệu");
        }
        
        // Sử dụng phương thức 'prepare' của mysqli để chuẩn bị câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            die('Lỗi chuẩn bị câu lệnh SQL: ' . $this->conn->error);
        }

        return $stmt;
    }

}
?>