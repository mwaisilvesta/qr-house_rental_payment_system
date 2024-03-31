<?php

class UserAction {
    private $db;

    public function __construct() {
        include 'db_connect.php'; // Include your database connection script
        $this->db = $conn;
    }

    public function register($username, $password) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            // Check if the username (email) already exists in the 'tenants' table
            $stmt = $this->db->prepare("SELECT * FROM tenants WHERE email = ?");
            $stmt->bind_param("s", $username); // Assuming the 'email' column contains usernames
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 0) {
                return array("success" => false, "message" => "Username does not exist in tenants table");
            }
    
            // Check if the username already exists in the 'tenants_accounts' table
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
        } catch (Exception $e) {
            return array("success" => false, "message" => "Error: " . $e->getMessage());
        }
    }
    

    
    
    

    public function login($username, $password) {
        try {
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
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id']; // Set user ID in session
                $_SESSION['username'] = $user['username']; // Set username in session
                return array("success" => true, "message" => "Login successful");
            } else {
                return array("success" => false, "message" => "Incorrect password");
            }
        } catch (Exception $e) {
            return array("success" => false, "message" => "Error: " . $e->getMessage());
        }
    }
    
    
    public function updateProfile($username, $new_username, $new_password, $confirm_password) {
        try {
            // Check if either username or password is provided
            if (empty($new_username) && empty($new_password)) {
                return array("success" => false, "message" => "No changes requested.");
            }
    
            // Check if the new password and confirm password match
            if (!empty($new_password) && $new_password != $confirm_password) {
                return array("success" => false, "message" => "New password and confirm password do not match.");
            }
    
            // Hash the new password for security if provided
            $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;
    
            // Prepare and execute SQL statement to update user's profile
            $stmt = $this->db->prepare("UPDATE tenants_accounts SET username = IFNULL(?, username), password = IFNULL(?, password) WHERE username = ?");
            $stmt->bind_param("sss", $new_username, $hashed_password, $username);
    
            if ($stmt->execute()) {
                return array("success" => true, "message" => "Profile updated successfully");
            } else {
                return array("success" => false, "message" => "Failed to update profile");
            }
        } catch (Exception $e) {
            return array("success" => false, "message" => "Error: " . $e->getMessage());
        }
    }
    public function submitMaintenanceRequest($username, $request_text, $request_date, $house_no) {
        try {
            $stmt = $this->db->prepare("INSERT INTO maintenance_requests (username, request_text, request_date, house_no) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $request_text, $request_date, $house_no);

            if ($stmt->execute()) {
                return array("success" => true, "message" => "Request submitted successfully");
            } else {
                return array("success" => false, "message" => "Failed to submit request");
            }
        } catch (Exception $e) {
            return array("success" => false, "message" => "Error: " . $e->getMessage());
        }
    }

    public function getHouseNumbers() {
        $houses = array(); // Initialize an empty array to store house numbers
        
        // Assuming you have a connection to the database stored in $this->db
    
        // Prepare and execute SQL statement to retrieve house numbers
        $stmt = $this->db->prepare("SELECT house_no FROM houses");
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch house numbers and store them in the $houses array
        while ($row = $result->fetch_assoc()) {
            $houses[] = $row['house_no'];
        }
    
        // Return the array of house numbers
        return $houses;
    }
    public function getTenantUsernames() {
        $tenantUsernames = array(); // Array to store tenant usernames
        // Query to fetch tenant usernames from the database
        $sql = "SELECT username FROM tenants_accounts";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tenantUsernames[] = $row['username'];
            }
        }
        return $tenantUsernames;
    }

    // Existing code...

    


    
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
