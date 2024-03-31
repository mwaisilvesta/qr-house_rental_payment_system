<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$dueDate = $_POST['dueDate'];
$frequency = $_POST['frequency'];
$customFrequency = isset($_POST['customFrequency']) ? $_POST['customFrequency'] : null;
$notificationMessage = $_POST['notificationMessage'];
$selectedTenants = isset($_POST['selectedTenants']) ? $_POST['selectedTenants'] : array();

// Prepare SQL statement to insert reminders
$sql = "INSERT INTO reminders (due_date, frequency, custom_frequency, notification_message, user_id)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if statement preparation failed
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
}

// Send reminders to selected tenants
foreach ($selectedTenants as $tenant) {
    // Retrieve user ID for the selected tenant
    $sqlUserId = "SELECT user_id FROM tenants_accounts WHERE username = ?";
    $stmtUserId = $conn->prepare($sqlUserId);
    $stmtUserId->bind_param("s", $tenant);
    $stmtUserId->execute();
    $resultUserId = $stmtUserId->get_result();
    
    // Check if user ID was retrieved successfully
    if ($resultUserId->num_rows > 0) {
        $rowUserId = $resultUserId->fetch_assoc();
        $userId = $rowUserId['user_id'];

        // Bind parameters
        $stmt->bind_param("ssssi", $dueDate, $frequency, $customFrequency, $notificationMessage, $userId);

        // Execute SQL statement
        if ($stmt->execute() !== TRUE) {
            echo "Error inserting reminder for user ID: $userId. Error: " . $stmt->error;
        }
    } else {
        echo "Error: User ID not found for username: $tenant";
    }
    
    // Close the statement for retrieving user ID
    $stmtUserId->close();
}

// Close statement
$stmt->close();

echo "Reminders successfully set.";

// Close connection
$conn->close();
?>
