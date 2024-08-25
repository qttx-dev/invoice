<?php
class Payment {
    private $conn;
    private $table = 'payments';

    public $id;
    public $invoice_id;
    public $amount;
    public $payment_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET invoice_id=?, amount=?, payment_date=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ids", $this->invoice_id, $this->amount, $this->payment_date);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readByInvoice($invoiceId) {
        $query = "SELECT * FROM " . $this->table . " WHERE invoice_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
