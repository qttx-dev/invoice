<?php
class Customer {
    private $conn;
    private $table = 'customers';

    public $id;
    public $name;
    public $email;
    public $address;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=?, email=?, address=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->name, $this->email, $this->address);
        
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

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->address = $row['address'];
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET name=?, email=?, address=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $this->name, $this->email, $this->address, $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
