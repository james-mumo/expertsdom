<?php
session_start();
include('includes/dbconnection.php');

// Check if 'del' and 'id' are set in the POST request
if (isset($_POST["del"]) && isset($_POST["id"])) {
    // Sanitize the input
    $id = mysqli_real_escape_string($con, $_POST["id"]);

    // Debugging output for server-side logs
    error_log("Attempting to delete assignment with ID: $id");

    // Execute the query
    $query = mysqli_query($con, "DELETE FROM assignments WHERE id = '$id'");

    if ($query) {
        // On success, set session message and return success response
        $_SESSION["delmsg"] = "Assignment deleted !!";
        echo json_encode([
            "status" => "success",
            "message" => "Deletion successful.",
        ]);
    } else {
        // On failure, log the error and return an error response
        $error = mysqli_error($con);
        $_SESSION["delmsg"] = "Error deleting assignment: " . $error;

        error_log("SQL Error: $error"); // Log database errors
        echo json_encode([
            "status" => "error",
            "message" => "Deletion failed: " . $error,
        ]);
    }
    // Prevent further output
    exit();
} else {
    // Handle missing parameters
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request. Required parameters not provided.",
    ]);
    // exit();
}
