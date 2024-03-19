<?php
session_start();
include 'db_connect.php';
include 'UserAction.php'; // Include your UserAction class file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAction = new UserAction($conn);
    $response = $userAction->submitMaintenanceRequest($_POST['requestText'], $_POST['requestDate']);
    echo json_encode($response);
}
?>
