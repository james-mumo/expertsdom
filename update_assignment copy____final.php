<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $work_is_done = mysqli_real_escape_string($con, $_POST['work_is_done']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']); // Get the comments

    // Update the work_is_done status and comments
    $update_query = "UPDATE assignments SET work_is_done = '$work_is_done', comments = '$comments' WHERE id = '$id'";
    
    $file = $_FILES["work_results"]["name"];
    // Replace spaces with underscores in the filename
    $file = str_replace(' ', '_', $file);

    // Optional: You can also sanitize the filename to remove other unwanted characters
    $file = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file);

    if ($file) {
    $result = mysqli_query($con, "SELECT work_results FROM assignments WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    $existing_files = $row['work_results'];

    // If there are existing files, append the new file with '@@@'
    if ($existing_files) {
        $new_files = $existing_files . '@@@' . $file;
    } else {
        $new_files = $file;
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["work_results"]["tmp_name"], "uploads/" . $file)) {
        // Update the database with the new file names
        $file_update_query = "UPDATE assignments SET work_results = '$new_files' WHERE id = '$id'";

        if (mysqli_query($con, $file_update_query)) {
            echo json_encode(["status" => "success", "message" => "File uploaded and assignment updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving file name in the database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error uploading the file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded."]);
}

}
?>

<!-- 
<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $work_is_done = mysqli_real_escape_string($con, $_POST['work_is_done']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']);


    
    // Update the work_is_done status
    $update_query = "UPDATE assignments SET work_is_done = '$work_is_done', comments = '$comments' WHERE id = '$id'";
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
?> -->
