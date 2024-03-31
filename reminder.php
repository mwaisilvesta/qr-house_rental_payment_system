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

// Prepare SQL statement
$sql = "INSERT INTO reminders (due_date, frequency, custom_frequency, notification_message)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $dueDate, $frequency, $customFrequency, $notificationMessage);

// Execute SQL statement
if ($stmt->execute() === TRUE) {
    echo "Reminder successfully set.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
