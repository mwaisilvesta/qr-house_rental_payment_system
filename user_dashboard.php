<?php
session_start();
include 'user_action.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve username from the session
$username = $_SESSION['username'];
?>
<<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('rent.png');
            background-size: cover;
            background-position: center;
        }
        .nav-container {
            width: 100%;
            background-color: #007bff;
            padding: 20px 0;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline-block;
            margin-right: 20px;
        }
        nav ul li a {
            display: block;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav ul li a:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .footer {
            background-color: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center; /* Center align the footer content */
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer-content {
            display: flex;
            justify-content: space-around; /* Distribute content evenly */
            align-items: center; /* Center align vertically */
            flex-wrap: wrap; /* Allow content to wrap */
        }
        .footer-content div {
            flex: 1; /* Equal distribution of space */
            margin: 10px; /* Add spacing between elements */
        }
        .footer-content ul {
            list-style: none;
            padding-left: 0;
        }
        .footer-content ul li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div class="nav-container">
        <nav>
            <ul>
                <li><a href="user_profileupdate.php"><i class="fas fa-user"></i> Update Profile</a></li>
                <li><a href="make_payment.php"><i class="fas fa-money-bill-wave"></i> Make Rent Payment</a></li>
                <li><a href="maintainance_request.php"><i class="fas fa-tools"></i> Maintenance Request</a></li>
                <li><a href="retrieving_message.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="history.php"><i class="fas fa-history"></i> Payment History</a></li>
                <li><a href="user_login.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <div class="content">
            <h1>Hello, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>!</h1>
            <p>Thank you for partnering with us. Here you can manage your rental payments and maintenance requests.</p>
        </div>
    </div>

    <footer class="footer">
    <div class="footer-content">
        <div>
            <h3>Contact Information:</h3>
            <ul>
                <li><i class="fas fa-envelope"></i> Email: pazuri@gmail.com</li>
                <li><i class="fas fa-map-marker-alt"></i> Address: Mayor Road 8, Rongai, Kenya</li>
                <li><i class="fas fa-phone"></i> Phone: +1234567890</li>
            </ul>
        </div>
        <div>
            <h3>Follow Us:</h3>
            <ul>
                <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
            </ul>
        </div>
        <div>
            <h3>FAQs - Payment:</h3>
            <ul>
                <li><strong>Q:</strong> How can I make a rent payment? <span class="answer">A: You can make a rent payment either by scanning the MPESA QR code provided or by using your card through our Stripe payment system.</span></li>
                <li><strong>Q:</strong> What payment methods do you accept? <span class="answer">A: We accept payments through MPESA using QR code scanning and card payments via Stripe.</span></li>
                <li><strong>Q:</strong> Is there a late payment fee? <span class="answer">A: Yes, late payments may incur a late payment fee. Please ensure payments are made on time to avoid any additional charges.</span></li>
                <li><strong>Q:</strong> How can I view my payment history? <span class="answer">A: You can view your payment history by visiting the "Payment History" section in your user dashboard after logging in.</span></li>
            </ul>
        </div>
    </div>
</footer>

<script>
    // JavaScript to toggle visibility of answers when question is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('li strong');
        questions.forEach(question => {
            question.addEventListener('click', () => {
                question.nextElementSibling.classList.toggle('show');
            });
        });
    });
</script>

<style>
    .answer {
        display: none;
    }
    .answer.show {
        display: block;
    }
</style>
