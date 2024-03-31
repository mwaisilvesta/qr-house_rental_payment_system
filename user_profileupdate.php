<?php
include 'user_action.php';
session_start(); // Start the session

// Include the UserAction class
include 'UserAction.php';

// Initialize UserAction object
$userAction = new UserAction();

// Initialize result message
$result_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Extract form data
    $current_username = $_SESSION['username']; // Get current username from session
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Call updateProfile method to update profile
    $result = $userAction->updateProfile($current_username, $new_username, $new_password, $confirm_password);

    // Update result message
    $result_message = $result['message'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-image: url('rent.png');
            background-size: cover;
            background-position: center;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .result-message {
            margin-top: 20px;
            text-align: center;
        }

        .result-message p {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>

        <!-- Display result message if form submitted -->
        <?php if ($result_message): ?>
        <div class="result-message">
            <p><?php echo $result_message; ?></p>
        </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="current_username">Current Username:</label>
                <input type="text" id="current_username" name="current_username" value="<?php echo $_SESSION['username']; ?>" disabled>
            </div>

            <div class="form-group">
                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username">
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>

            <div class="form-group">
                <button type="submit" name="update_profile">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
