<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests</title>
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-requests {
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Maintenance Requests</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "house_rental_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a request to delete a record is made
        if(isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];
            $sql = "DELETE FROM maintenance_requests WHERE id = $delete_id";
            if ($conn->query($sql) === TRUE) {
                // Record deleted successfully, redirect to the same page
                header("Location: {$_SERVER['PHP_SELF']}");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        // Display maintenance requests
        $sql = "SELECT id, request_text, request_date, house_no, username FROM maintenance_requests";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Request Text</th><th>Request Date</th><th>house_no</th><th>tenant</th><th>Action</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["request_text"]."</td>";
                echo "<td>".$row["request_date"]."</td>";
                echo "<td>".$row["house_no"]."</td>";
                echo "<td>".$row["username"]."</td>";
                echo "<td><a href=\"{$_SERVER['PHP_SELF']}?delete_id={$row['id']}\">Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-requests'>No maintenance requests found.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
