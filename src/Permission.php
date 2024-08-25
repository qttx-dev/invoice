<?php
class Permission {
    private $conn;
    private $table = 'role_permissions';

    public $role_id;
    public $module_id;
    public $can_access;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setPermission() {
        $query = "INSERT INTO " . $this->table . " SET role_id=?, module_id=?, can_access=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $this->role_id, $this->module_id, $this->can_access);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkPermission($role_id, $module_id) {
        $query = "SELECT can_access FROM " . $this->table . " WHERE role_id = ? AND module_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $role_id, $module_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['can_access'] ?? false;
    }
}
