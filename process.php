<?php
// Include the UserAction class file
include 'user_action.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the requestText and requestDate are set
    if (isset($_POST["request_text"]) && isset($_POST["request_date"])) {
        // Sanitize and store form data
        $request_text = htmlspecialchars($_POST["request_text"]);
        $request_date = htmlspecialchars($_POST["request_date"]);

        // Create an instance of UserAction
        $userAction = new UserAction();

        // Call the submitMaintenanceRequest method
        $result = $userAction->submitMaintenanceRequest($request_text, $request_date);

        // Check the result
        if ($result["success"]) {
            // Maintenance request submitted successfully, redirect to a success page
            header("Location: success.php");
            exit(); // Stop further execution
        } else {
            // Failed to submit maintenance request, redirect to the form page with error message
            header("Location: index.php?error=" . urlencode($result["message"]));
            exit(); // Stop further execution
        }
    } else {
        // Missing requestText or requestDate parameters, redirect to the form page with error message
        header("Location: index.php?error=" . urlencode("Missing parameters"));
        exit(); // Stop further execution
    }
} else {
    // Invalid request method, redirect to the form page with error message
    header("Location: index.php?error=" . urlencode("Invalid request method"));
    exit(); // Stop further execution
}
?>
