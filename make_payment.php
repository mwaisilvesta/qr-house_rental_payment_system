<?php
session_start();
include 'db_connect.php';
include 'user_actions.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// Get user ID from session (assuming user ID is stored in 'user_id' session variable)
$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Add transparency to the container */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            box-sizing: border-box;
        }
        h2 {
            color: #333;
            margin-top: 0;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #666;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .stripe-button {
            display: block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rental Payment</h2>
        <form action="" method="post">
            <!-- Removed email input -->
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" placeholder="Enter amount">
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button submit"
                    data-key="pk_test_51MrHUCFq0XdzIScyVqERfDveXZe4JrInGhNSlGJLjd9mA6LRxMnDpxahKfx1sMVYJcayjORgpbAIc155uJGV5dGP00El6RxIsy"
                    data-amount=""
                    data-name="Rental Payment"
                    data-description="Rental Payment"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-currency="kES"
                    data-email="">
            </script>
        </form>

        <?php
        require_once('vendor/autoload.php'); // Include the Stripe PHP library

        \Stripe\Stripe::setApiKey('sk_test_51MrHUCFq0XdzIScyp5WUnFR7rm2LUi6ygySTLwLV5LUcIYlQqZflwwYaDeH6WMtG0DtzLv27b8rgJVsFTqgq6y2R00ragPKPkm'); // Set your secret key here

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the amount and stripeToken from the form
            $amount = $_POST['amount'];
            $stripeToken = $_POST['stripeToken'];

            try {
                // Create a charge
                $charge = \Stripe\Charge::create(array(
                    'amount' => $amount * 100, // amount in cents
                    'currency' => 'KES',
                    'source' => $stripeToken,
                    'description' => 'Rental Payment'
                ));

                // Payment successful
                echo '<h3 style="color: #4CAF50; text-align: center;">Payment successful!</h3>';
                echo '<p style="text-align: center;">Charge ID: ' . $charge->id . '</p>';

                // Save payment details to database using the user ID
                $servername = "localhost";
                $username = "root"; // Your MySQL username
                $password = ""; // Your MySQL password
                $dbname = "house_rental_db"; // Your database name

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Insert payment details into database
                $sql = "INSERT INTO stripe_payments (email, amount, charge_id) VALUES ('$user_id', '$amount', '$charge->id')";

                if ($conn->query($sql) === TRUE) {
                    echo '<p style="text-align: center;">Payment details saved successfully.</p>';
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $conn->close();

            } catch (\Stripe\Exception\CardException $e) {
                // Payment failed
                echo '<h3 style="color: #FF5733; text-align: center;">Payment failed!</h3>';
                echo '<p style="text-align: center;">Error: ' . $e->getError()->message . '</p>';
            }
        }
        ?>
    </div>
</body>
</html>
