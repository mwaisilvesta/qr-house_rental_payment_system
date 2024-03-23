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
            background-image: url('rent.png'); /* Add the path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
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
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "house_rental_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $request_text = $_POST['request_text'];
            $request_date = $_POST['request_date'];
            $house_no = $_POST['house_no'];

            $stmt = $conn->prepare("INSERT INTO maintenance_requests (request_text, request_date, house_no) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $request_text, $request_date, $house_no);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Request submitted successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error submitting request.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="house_no">House Number:</label><br>
            <select id="house_no" name="house_no">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "house_rental_db";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT house_no FROM houses"; // Assuming 'houses' is the table name storing house numbers
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["house_no"] . "'>" . $row["house_no"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No houses found</option>";
                }

                $conn->close();
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
