<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch messages for the logged-in user
$username = $_SESSION['username'];

$sql = "SELECT m.*, ta.username AS receiver_username
        FROM messages m
        INNER JOIN tenants_accounts ta ON m.receiver_id = ta.id
        WHERE ta.username = ? 
        ORDER BY m.timestamp DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .message-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
            border-radius: 8px;
        }

        .message-header {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .message-content {
            margin-bottom: 5px;
            color: #555;
        }

        .message-timestamp {
            color: #777;
            font-size: 12px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
    <h2> Messages</h2>
    <h3>Your friendly reminder awaits your attention! <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h3>
    <?php
    // Display messages
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $receiver_username = $row['receiver_username'];
            $message_content = $row['message'];
            $timestamp = $row['timestamp'];
    ?>
    <div class="message-container">
        
        <div class="message-content"><?php echo $message_content; ?></div>
        <div class="message-timestamp">Sent on: <?php echo $timestamp; ?></div>
    </div>
    <?php
        }
    } else {
        echo "<p>No messages found for $username.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
    
</body>
</html>
