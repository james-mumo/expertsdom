<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['view_id'])) {
    $id = $_POST['view_id'];
    $query = mysqli_query($con, "SELECT * FROM assignments WHERE id = '$id'");
    $row = mysqli_fetch_array($query);

    echo "<h4>Assignment Details</h4>";
    echo "<p><strong>Deadline:</strong> " . htmlentities($row['deadline']) . "</p>";
    echo "<p><strong>Subject:</strong> " . htmlentities($row['subject']) . "</p>";
    echo "<p><strong>Topic:</strong> " . htmlentities($row['topic']) . "</p>";
    echo "<p><strong>Pages:</strong> " . htmlentities($row['pages']) . "</p>";
    echo "<p><strong>Instructions:</strong> " . htmlentities($row['instructions']) . "</p>";

    // Checkbox to mark as done
    echo "<div class='form-group'>";
    echo "<label><input type='checkbox' id='work_done' data-id='" . $row['id'] . "'" . ($row['work_is_done'] ? ' checked' : '') . "> Mark as Done</label>";
    echo "</div>";

    // File upload input
    echo "<div class='form-group'>";
    echo "<label for='work_results'>Attach File:</label>";
    echo "<input type='file' id='work_results' class='form-control' />";
    echo "</div>";

    // Button to save changes
    echo "<button type='button' class='btn btn-success' id='save_changes' data-id='" . $row['id'] . "'>Save Changes</button>";
    
    // Button to delete assignment
    echo "<button type='button' class='btn btn-danger' id='delete_assignment' data-id='" . $row['id'] . "'>Delete Assignment</button>";
}
?>
