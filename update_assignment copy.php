<!-- <?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $work_is_done = mysqli_real_escape_string($con, $_POST['work_is_done']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']); // Get the comments

    // Update the work_is_done status and comments
    $update_query = "UPDATE assignments SET work_is_done = '$work_is_done', comments = '$comments' WHERE id = '$id'";
    
    $file = $_FILES["work_results"]["name"];
    if ($file) {
    if (mysqli_query($con, $update_query)) {
            move_uploaded_file($_FILES["work_results"]["tmp_name"], "uploads/" . $file);
        }

        $file_update_query = "UPDATE assignments SET work_results = '$file' WHERE id = '$id'";
        if (mysqli_query($con, $file_update_query)) {
            echo json_encode(["status" => "success", "message" => "File uploaded and assignment updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving file name in the database."]);
        }
        
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating assignment."]);
    }
}
?> -->


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
        $upload_dir = 'uploads/'; // Ensure this directory exists and has write permissions

        // Check for valid file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($_FILES['work_results']['type'], $allowed_types)) {
            echo json_encode(["status" => "error", "message" => "Invalid file type."]);
            exit;
        }

        // Check if the file is within the size limit (optional)
        if ($_FILES['work_results']['size'] > 5000000) { // 5MB max size
            echo json_encode(["status" => "error", "message" => "File size is too large."]);
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // Save the file name to the database
            $file_update_query = "UPDATE assignments SET work_results = '$file_name' WHERE id = '$id'";
            if (mysqli_query($con, $file_update_query)) {
                echo json_encode(["status" => "success", "message" => "File uploaded successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error saving file name in the database."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
        }
    } else {
        echo json_encode(["status" => "success", "message" => "Work marked as done. No file uploaded."]);
    }
}
?>
