<?php
class Reminder {
    private $conn;
    private $table = 'reminders';

    public $id;
    public $invoice_id;
    public $reminder_date;
    public $message;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET invoice_id=?, reminder_date=?, message=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $this->invoice_id, $this->reminder_date, $this->message);
        
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
