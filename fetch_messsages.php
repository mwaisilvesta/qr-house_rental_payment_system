<?php
session_start();
include './db_connect.php'; // Include your database connection file

// Fetch messages from the database
$sqlFetchMessages = "SELECT m.*, u.username AS sender_username FROM messages m JOIN tenants_accounts u ON m.sender_id = u.id WHERE receiver_id = ?";
$stmtFetchMessages = $conn->prepare($sqlFetchMessages);
$stmtFetchMessages->bind_param("i", $_SESSION['user_id']);
$stmtFetchMessages->execute();
$resultFetchMessages = $stmtFetchMessages->get_result();
$messages = array();

// Store fetched messages in an array
while ($row = $resultFetchMessages->fetch_assoc()) {
    $messages[] = $row;
}

// Close statement
$stmtFetchMessages->close();

// Close connection
$conn->close();

// Encode messages as JSON and return
header('Content-Type: application/json');
echo json_encode($messages);
?>
