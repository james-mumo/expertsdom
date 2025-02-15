<?php
session_start();
include('includes/dbconnection.php');

// Ensure the user is logged in
if (!isset($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
}

// Check if an assignment ID is provided via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $delete_id = intval($_POST['id']); // Ensure it's an integer

    // Prepare delete query
    $stmt = $con->prepare("DELETE FROM assignments WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Assignment deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete the assignment.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request. Required parameters not provided.";
}

// Redirect back to assignments page
header("Location: assignments_list.php");
exit();
