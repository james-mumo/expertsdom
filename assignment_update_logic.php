<?php
include "includes/dbconnection.php"; // Ensure $con is properly set

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$con) {
    die("Database connection failed.");
}

if (isset($_POST['update'])) {
    $assignment_id = $_POST['assignment_id'];
    $amount_paid = $_POST['amount_paid'];
    $work_is_done = isset($_POST['work_is_done']) ? $_POST['work_is_done'] : 0;
    $file_path = "";

    // Log values to console
    echo "<script>console.log('Assignment ID: " . $assignment_id . "');</script>";
    echo "<script>console.log('Amount Paid: " . $amount_paid . "');</script>";
    echo "<script>console.log('Work is Done: " . $work_is_done . "');</script>";

    // Handle file upload if present
    if (isset($_FILES["work_results"]) && $_FILES["work_results"]["name"] != "") {
        $file = $_FILES["work_results"]["name"];
        // Sanitize filename
        $file = str_replace(' ', '_', $file);
        $file = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file);

        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . $file;

        // Fetch existing file records
        $result = mysqli_query($con, "SELECT work_results FROM assignments WHERE id = '$assignment_id'");
        $row = mysqli_fetch_assoc($result);
        $existing_files = $row['work_results'];

        // Append new file if there are existing ones
        if ($existing_files) {
            $new_files = $existing_files . '@@@' . $file_path;
        } else {
            $new_files = $file_path;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES["work_results"]["tmp_name"], $file_path)) {
            // Update file in database
            $file_update_query = "UPDATE assignments SET work_results = '$new_files' WHERE id = '$assignment_id'";
            if (!mysqli_query($con, $file_update_query)) {
                die("Error updating file path: " . mysqli_error($con));
            }
            echo "<script>console.log('File uploaded: " . $file_path . "');</script>";
        } else {
            die("Error uploading the file.");
        }
    }

    // Update query for assignment details
    $query = "UPDATE assignments SET amount_paid = ?, work_is_done = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("dii", $amount_paid, $work_is_done, $assignment_id);

    if ($stmt->execute()) {
        echo "Assignment updated successfully.";
        echo "<script>console.log('Update successful');</script>";
    } else {
        echo "Error updating assignment: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
