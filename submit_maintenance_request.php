<?php
// Include the UserAction class file
include 'user_action.php';
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the requestText and requestDate are set
    if (isset($_POST["request_text"]) && isset($_POST["request_date"])) {
        // Sanitize and store form data
        $request_text = htmlspecialchars($_POST["request_text"]);
        $request_date = htmlspecialchars($_POST["request_date"]);

        // Create an instance of UserAction
        $userAction = new UserAction(); // Corrected variable name

        // Call the submitMaintenanceRequest method
        $result = $userAction->submitMaintenanceRequest($request_text, $request_date); // Corrected method call

        // Check the result
        if ($result["success"]) {
            // Maintenance request submitted successfully
            echo json_encode(array("success" => true, "message" => "Maintenance request submitted successfully"));
        } else {
            // Failed to submit maintenance request
            echo json_encode(array("success" => false, "message" => $result["message"]));
        }
    } else {
        // Missing requestText or requestDate parameters
        echo json_encode(array("success" => false, "message" => "Missing parameters"));
    }
} else {
    // Invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
