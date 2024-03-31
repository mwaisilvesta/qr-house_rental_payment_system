<?php
session_start();
include 'user_action.php';
if (isset($_SESSION['username'])) {
    // The username session variable is set
    // You can proceed with using it
    $username = $_SESSION['username'];
} else {
    // The username session variable is not set
    // Handle the case where the user is not logged in or the session variable is not set
    // For example, redirect to the login page or display a message
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('rent.png');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }
        .nav-container {
            width: 200px;
            margin-right: 20px;
        }
        nav {
            background-color: #007bff;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin-bottom: 10px;
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
            display: flex;
        }
        .content {
            flex: 1;
        }
        .panel {
            display: none;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin-top: 20px;
        }
        .panel h3 {
            margin-top: 0;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="email"],
        button {
            width: calc(100% - 30px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- Navigation bar -->
<div class="nav-container">
    <nav>
        <ul>
            <li><a href="user_profileupdate.php"><i class="fas fa-user"></i> Update Profile</a></li>
            <li><a href="make_payment.php"><i class="fas fa-money-bill-wave"></i> Make Rent Payment</a></li>
            <li><a href="maintainance_request.php"><i class="fas fa-tools"></i> Maintenance Request</a></li>
            <li><a href="retrieving_message.php"><i class="fas fa-bell"></i> Notifications</a></li>
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
</body>
</html>
