<?php

class UserAction {
    private $db;

    public function __construct() {
        include 'db_connect.php'; // Include your database connection script
        $this->db = $conn;
    }

    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $stmt = $this->db->prepare("SELECT * FROM tenants_accounts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return array("success" => false, "message" => "Username already exists");
        }

        // Insert user details into tenants_accounts table
        $stmt = $this->db->prepare("INSERT INTO tenants_accounts (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Registration successful");
        } else {
            return array("success" => false, "message" => "Registration failed");
        }
    }

    public function login($username, $password) {
        // Check if the username exists
        $stmt = $this->db->prepare("SELECT * FROM tenants_accounts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return array("success" => false, "message" => "Username not found");
        }

        // Fetch user details from the database
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables or return user data as needed
            $_SESSION['user_id'] = $user['id']; // Example: Setting user ID in session
            return array("success" => true, "message" => "Login successful");
        } else {
            return array("success" => false, "message" => "Incorrect password");
        }
    }

    public function submitMaintenanceRequest($requestText, $requestDate) {
        // Prepare and execute SQL statement to insert maintenance request into the database
        $stmt = $this->db->prepare("INSERT INTO maintenance_requests (request_text, request_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $requestText, $requestDate);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Maintenance request submitted successfully");
        } else {
            return array("success" => false, "message" => "Failed to submit maintenance request");
        }
    }
    
    public function logout() {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        header("Location: login.php");
        exit();
    }
}

?>
