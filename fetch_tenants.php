<?php
// fetch_tenants.php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_db";

// Establishing database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch list of tenants from the database
$sql = "SELECT id, username FROM tenants_accounts";
$result = $conn->query($sql);

// Initialize an empty array to store the fetched data
$tenants = [];

// Check if there are any rows returned from the query
if ($result->num_rows > 0) {
    // Fetch each row and add it to the $tenants array
    while ($row = $result->fetch_assoc()) {
        // Ensure keys match JavaScript expectations
        $tenants[] = [
            "id" => $row["id"],
            "name" => $row["username"]
        ];
    }
}

// Encode the array as JSON and return it
header('Content-Type: application/json');
echo json_encode($tenants);

// Close the database connection
$conn->close();
?>
