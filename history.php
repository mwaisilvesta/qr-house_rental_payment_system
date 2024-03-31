<?php
session_start();
include 'db_connect.php';
include 'user_actions.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// Fetch payment history for the logged-in user
$username = $_SESSION['username'];

$sql = "SELECT p.*
        FROM payments p
        INNER JOIN tenants ta ON p.tenant_id = ta.id
        WHERE ta.email = ?
        ORDER BY p.date_created DESC";

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
    <title>My Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .payment-container {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .payment-details {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .payment-amount {
            margin-bottom: 5px;
            color: #555;
        }

        .payment-date {
            color: #777;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: #333;
        }

        .header h3 {
            color: #777;
        }

        .no-payment {
            text-align: center;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h2>Payment History</h2>
        <h3>Hello, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h3>
    </div>

    <div class="payment-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $payment_amount = $row['amount'];
                $payment_date = $row['date_created'];
        ?>
        <div class="payment-container">
            <div class="payment-details">Payment Amount: $<?php echo $payment_amount; ?></div>
            <div class="payment-date">Payment Date: <?php echo $payment_date; ?></div>
        </div>
        <?php
            }
        } else {
            echo "<div class='no-payment'>No payment history found for $username.</div>";
        }
        ?>
    </div>
    
</body>
</html>
