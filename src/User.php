<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
        $query = "INSERT INTO " . $this->table . " SET username=?, email=?, password=?";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            return true;
        }

        return "Registrierung fehlgeschlagen: " . $stmt->error;
    }

    public function login($username, $password) {
        $query = "SELECT id, username, password FROM " . $this->table . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                return true;
            }
        }

        return "Ungültige Anmeldeinformationen.";
    }
}
