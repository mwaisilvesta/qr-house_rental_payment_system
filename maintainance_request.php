<?php
include 'user_action.php';
session_start();

$userAction = new UserAction();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_text = $_POST['request_text'];
    $request_date = $_POST['request_date'];
    $house_no = $_POST['house_no'];
    $username = $_SESSION['username'];

    $result = $userAction->submitMaintenanceRequest($username, $request_text, $request_date, $house_no);

    if ($result['success']) {
        $message = "<p style='color: green;'>Request submitted successfully!</p>";
    } else {
        $message = "<p style='color: red;'>Error submitting request: " . $result['message'] . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        textarea, input[type="date"], input[type="submit"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .faq-toggle {
            text-align: center;
            cursor: pointer;
            color: #007bff;
            margin-top: 20px;
        }

        .faq {
            display: none;
            padding-top: 20px;
        }

        .faq h3 {
            color: #333;
        }

        .faq p {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Maintenance Request Form</h2>
        <?php if ($message) echo $message; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="house_no">House Number:</label><br>
            <select id="house_no" name="house_no">
                <?php
                $houses = $userAction->getHouseNumbers();
                foreach ($houses as $house) {
                    echo "<option value='" . $house . "'>" . $house . "</option>";
                }
                ?>
            </select><br>
            <label for="request_text">Request Text:</label><br>
            <textarea id="request_text" name="request_text" rows="4" cols="50"></textarea><br>
            <label for="request_date">Request Date:</label><br>
            <input type="date" id="request_date" name="request_date"><br><br>
            <input type="submit" value="Submit">
        </form>
        <div class="faq-toggle" onclick="toggleFAQs()">Show FAQs</div>
        <div class="faq">
            <h3>Frequently Asked Questions (FAQs)</h3>
            <!-- FAQ content remains unchanged -->
        </div>
    </div>

    <script>
        function toggleFAQs() {
            var faq = document.querySelector('.faq');
            if (faq.style.display === 'none') {
                faq.style.display = 'block';
                document.querySelector('.faq-toggle').innerText = 'Hide FAQs';
            } else {
                faq.style.display = 'none';
                document.querySelector('.faq-toggle').innerText = 'Show FAQs';
            }
        }
    </script>
</body>
</html>
