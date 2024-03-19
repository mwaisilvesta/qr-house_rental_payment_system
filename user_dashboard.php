<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        p {
            color: #666;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            background-color: #dc3545;
        }
        /* Navigation bar styles */
        nav {
            background-color: #333;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
        }
        /* Panel styles */
        .panel {
            display: none;
            padding: 20px;
            background-color: #f3f3f3;
            border-radius: 8px;
            margin-top: 20px;
        }
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <nav>
        <ul>
            <li><a href="make_payment.php">Rent Payment</a></li>
            <li><a href="maintainance_request.php">Maintenance Request</a></li>
            <li><a href="#" id="show-profile">Update User Details</a></li>
            <li><a href="user_login.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <h2>Welcome to Your Rental Payment System</h2>
        <p>Hello, tenant! thank you for patnering with us.</p>
        
        <!-- User Profile Panel -->
        <div class="panel" id="profile-panel">
            <h3>User Profile</h3>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="JohnDoe" disabled><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="johndoe@example.com"><br>
                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <script>
        // Function to toggle profile panel visibility
        document.getElementById('show-profile').addEventListener('click', function() {
            var panel = document.getElementById('profile-panel');
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        });
    </script>
</body>
</html>
