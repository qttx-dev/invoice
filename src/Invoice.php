<?php
class Invoice {
    private $conn;
    private $table = 'invoices';

    public $id;
    public $customer_id;
    public $invoice_number;
    public $invoice_date;
    public $due_date;
    public $total_amount;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET customer_id=?, invoice_number=?, invoice_date=?, due_date=?, total_amount=?, status=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssds", $this->customer_id, $this->invoice_number, $this->invoice_date, $this->due_date, $this->total_amount, $this->status);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function read() {
        $query = "SELECT i.*, c.name as customer_name FROM " . $this->table . " i LEFT JOIN customers c ON i.customer_id = c.id";
        $result = $this->conn->query($query);
        return $result;
    }

    public function readOne() {
        $query = "SELECT i.*, c.name as customer_name FROM " . $this->table . " i LEFT JOIN customers c ON i.customer_id = c.id WHERE i.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET customer_id=?, invoice_number=?, invoice_date=?, due_date=?, total_amount=?, status=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssdsi", $this->customer_id, $this->invoice_number, $this->invoice_date, $this->due_date, $this->total_amount, $this->status, $this->id);
        
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

    public function generateInvoiceNumber() {
        $query = "SELECT MAX(CAST(SUBSTRING(invoice_number, 5) AS UNSIGNED)) as max_number FROM " . $this->table;
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        $max_number = $row['max_number'];
        $next_number = $max_number + 1;
        return 'INV-' . str_pad($next_number, 6, '0', STR_PAD_LEFT);
    }

    public function readByCustomer($customerId) {
        $query = "SELECT * FROM " . $this->table . " WHERE customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
}
