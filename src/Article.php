<?php
class Article {
    private $conn;
    private $table = 'articles';

    public $id;
    public $name;
    public $description;
    public $price;
    public $unit;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET name=?, description=?, price=?, unit=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssds", $this->name, $this->description, $this->price, $this->unit);
        
        if($stmt->execute()) {
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
        $this->description = $row['description'];
        $this->price = $row['price'];
        $this->unit = $row['unit'];
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . " SET name=?, description=?, price=?, unit=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdsi", $this->name, $this->description, $this->price, $this->unit, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    

    // Weitere Methoden wie read(), update(), delete() hier hinzufügen
}
