<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
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

        .btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
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
    <h2>All Messages</h2>
    <?php
    // PHP code to fetch and display messages
    session_start();
    include 'db_connect.php';

    // Fetch all messages from the database
    $sql = "SELECT m.*, ta.username AS receiver_username
            FROM messages m
            INNER JOIN tenants_accounts ta ON m.receiver_id = ta.id
            ORDER BY m.timestamp DESC";
    $result = $conn->query($sql);

    // Check if any messages are found
    if ($result->num_rows > 0) {
        // Loop through each message and display them
        while ($row = $result->fetch_assoc()) {
            $receiver_username = $row['receiver_username'];
            $message_content = $row['message'];
            $timestamp = $row['timestamp'];
    ?>
    <div class="message-container">
        <div class="message-header">To: <?php echo $receiver_username; ?></div>
        <div class="message-content"><?php echo $message_content; ?></div>
        <div class="message-timestamp">Sent on: <?php echo $timestamp; ?></div>
    </div>
    <?php
        }
    } else {
        // No messages found
        echo "<p>No messages found.</p>";
    }
    // Close the database connection
    $conn->close();
    ?>
    <!-- Buttons -->
    <div class="btn-container">
        <button class="btn" onclick="window.location.href = 'sending_messages.html';">Send Reminder</button>
    </div>
</body>
</html>
