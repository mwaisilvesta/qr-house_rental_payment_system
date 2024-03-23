<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests - Admin</title>
    <style>
        /* Basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Maintenance Requests - Admin</h2>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Request Text</th>
                    <th>Request Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include your database connection file here
                include 'db_connection.php';

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch maintenance requests from the database
                $sql = "SELECT * FROM maintenance_requests";
                $result = $conn->query($sql);

                if ($result === false) {
                    // Handle query error
                    die("Error executing query: " . $conn->error);
                }

                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["request_id"] . "</td>";
                        echo "<td>" . $row["request_text"] . "</td>";
                        echo "<td>" . $row["request_date"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No maintenance requests found</td></tr>";
                }

                // Close database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
