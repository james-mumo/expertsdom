<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $work_is_done = mysqli_real_escape_string($con, $_POST['work_is_done']);
    
    // Update the work_is_done status
    $update_query = "UPDATE assignments SET work_is_done = '$work_is_done' WHERE id = '$id'";
    mysqli_query($con, $update_query);

    // Handle file upload
    if (isset($_FILES['work_results']) && $_FILES['work_results']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['work_results']['tmp_name'];
        $file_name = basename($_FILES['work_results']['name']);
        $upload_dir = 'uploads/'; // Make sure this directory exists and is writable

        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // You can also save the file name to the database if needed
            // mysqli_query($con, "UPDATE assignments SET work_results = '$file_name' WHERE id = '$id'");
            echo json_encode(["status" => "success", "message" => "File uploaded successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "File upload failed."]);
        }
    } else {
        echo json_encode(["status" => "success", "message" => "Work marked as done. No file uploaded."]);
    }
}
?>
