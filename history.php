<?php
session_start();
include 'db_connect.php';
include 'user_actions.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch payment history for the logged-in user from the QR code payments table
$sql_qr = "SELECT p.amount, p.date_created
           FROM payments p
           INNER JOIN tenants ta ON p.tenant_id = ta.id
           WHERE ta.email = ?
           ORDER BY p.date_created DESC";

$stmt_qr = $conn->prepare($sql_qr);
$stmt_qr->bind_param("s", $_SESSION['username']);
$stmt_qr->execute();
$result_qr = $stmt_qr->get_result();

// Fetch payment history for the logged-in user from the Stripe payments table
$sql_stripe = "SELECT sp.amount, sp.created_at AS date_created
               FROM stripe_payments sp
               WHERE sp.tenants_id = ?
               ORDER BY sp.created_at DESC";

$stmt_stripe = $conn->prepare($sql_stripe);
$stmt_stripe->bind_param("s", $user_id);
$stmt_stripe->execute();
$result_stripe = $stmt_stripe->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-image: url(''); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #333; /* Changed header color for better visibility */
        }

        .payment-list {
            margin-bottom: 20px;
        }

        .payment-list h3 {
            margin-bottom: 10px;
            color: #333;
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

        .payment-date {
            color: #777;
            font-size: 14px;
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

    <!-- Display QR code payments -->
    <div class="payment-list">
        <h3>QR Code Payments</h3>
        <?php
        if ($result_qr->num_rows > 0) {
            while ($row_qr = $result_qr->fetch_assoc()) {
                $payment_amount_qr = $row_qr['amount'];
                $payment_date_qr = $row_qr['date_created'];
        ?>
        <div class="payment-container">
            <div class="payment-details">Payment Amount: $<?php echo $payment_amount_qr; ?></div>
            <div class="payment-date">Payment Date: <?php echo $payment_date_qr; ?></div>
        </div>
        <?php
            }
        } else {
            echo "<div class='no-payment'>No QR code payment history found for $username.</div>";
        }
        ?>
    </div>

    <!-- Display Stripe payments -->
    <div class="payment-list">
        <h3>Stripe Payments</h3>
        <?php
        if ($result_stripe->num_rows > 0) {
            while ($row_stripe = $result_stripe->fetch_assoc()) {
                $payment_amount_stripe = $row_stripe['amount'];
                $payment_date_stripe = $row_stripe['date_created'];
        ?>
        <div class="payment-container">
            <div class="payment-details">Payment Amount: $<?php echo $payment_amount_stripe; ?></div>
            <div class="payment-date">Payment Date: <?php echo $payment_date_stripe; ?></div>
        </div>
        <?php
            }
        } else {
            echo "<div class='no-payment'>No Stripe payment history found for $username.</div>";
        }
        ?>
    </div>
</body>
</html>
