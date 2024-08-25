<?php
class InvoiceItem {
    private $conn;
    private $table = 'invoice_items';

    public $id;
    public $invoice_id;
    public $article_id;
    public $quantity;
    public $price;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET invoice_id=?, article_id=?, quantity=?, price=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iidd", $this->invoice_id, $this->article_id, $this->quantity, $this->price);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readByInvoice($invoice_id) {
        $query = "SELECT i.*, a.name as article_name FROM " . $this->table . " i LEFT JOIN articles a ON i.article_id = a.id WHERE i.invoice_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
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
