<?php
class Role {
    private $conn;
    private $table = 'roles';

    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->name);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result;
    }
}
