<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $receiverIds = $_POST['receiver_usernames']; // Array of receiver IDs
    $message = $_POST['message'];

    // Validate form data
    if (empty($receiverIds)) {
        echo "Error: No recipients selected.";
        exit();
    }

    if (empty($message)) {
        echo "Error: Message cannot be empty.";
        exit();
    }

    // Process the form data
    try {
        // Begin a transaction
        $conn->begin_transaction();

        // Loop through each receiver ID to insert messages
        foreach ($receiverIds as $receiver_id) {
            // Insert message into the database
            $stmtInsertMessage = $conn->prepare("INSERT INTO messages (receiver_id, message) VALUES (?, ?)");
            $stmtInsertMessage->bind_param("is", $receiver_id, $message);
            $stmtInsertMessage->execute();
        }

        // Commit the transaction if all queries succeed
        $conn->commit();

        // Close statements
        $stmtInsertMessage->close();

        // Close connection
        $conn->close();

        // Redirect the user to a success page or display a success message
        header("Location: view_messages.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();

        // Handle the error, log it, and display an error message to the user
        echo "Error: " . $e->getMessage();
        // You can also log the error using error_log() function

        // Close statements and connection
        $stmtInsertMessage->close();
        $conn->close();
    }
}
?>
