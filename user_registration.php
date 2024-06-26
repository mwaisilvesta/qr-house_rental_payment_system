<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* Basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            margin-top: 5px;
        }
        .login-link {
            text-align: center;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
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
                        // Password is correct, set session variables or return user data as needed
                        $_SESSION['user_id'] = $user['id']; // Example: Setting user ID in session
                        return array("success" => true, "message" => "Login successful");
                    } else {
                        return array("success" => false, "message" => "Incorrect password");
                    }
                } catch (Exception $e) {
                    return array("success" => false, "message" => "Error: " . $e->getMessage());
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Process registration form submission
            $userAction = new UserAction();
            $registration_result = $userAction->register($_POST['username'], $_POST['password']);
            $registration_message = $registration_result['message'];
            $registration_success = $registration_result['success'];
        }
        ?>
        <form id="registrationForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class
            <form id="registrationForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <div id="usernameError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <div id="passwordError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword">
                <div id="confirmPasswordError" class="error-message"></div>
            </div>
            <div class="form-group">
                <input type="submit" value="Register">
            </div>
        </form>
        <?php if (isset($registration_message)): ?>
            <p class="<?php echo $registration_success ? 'success-message' : 'error-message'; ?>"><?php echo $registration_message; ?></p>
        <?php endif; ?>
        <p class="login-link">Already have an account? <a href="user_login.php">Login</a></p>
    </div>

    <!-- Form validation script -->
    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            // Prevent form submission if validation fails
            if (!validateForm()) {
                event.preventDefault();
            }
        });

        function validateForm() {
            var username = document.getElementById("username").value.trim();
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            // Reset error messages
            document.getElementById("usernameError").textContent = "";
            document.getElementById("passwordError").textContent = "";
            document.getElementById("confirmPasswordError").textContent = "";

            var isValid = true;

            // Validate username
            if (username === "") {
                document.getElementById("usernameError").textContent = "Please enter a username.";
                isValid = false;
            }

            // Validate password
            if (password === "") {
                document.getElementById("passwordError").textContent = "Please enter a password.";
                isValid = false;
            } else if (password.length < 8) {
                document.getElementById("passwordError").textContent = "Password must be at least 8 characters long.";
                isValid = false;
            }

            // Validate confirm password
            if (confirmPassword === "") {
                document.getElementById("confirmPasswordError").textContent = "Please confirm your password.";
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>

