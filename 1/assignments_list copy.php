<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set("display_errors", 1); // Display errors on the screen
include "includes/dbconnection.php";

if (strlen($_SESSION["sid"]) == 0) {
    header("location:logout.php");
}

// Handle form submission from the modal
if (isset($_POST["submit_assignment"])) {
    // Collect form data
    $deadline = $_POST["deadline"];
    $time = $_POST["time"];
    $pages = $_POST["pages"];
    $subject = $_POST["subject"];
    $topic = $_POST["topic"];
    $resources = $_POST["resources"];
    $instructions = $_POST["instructions"];
    $priority_level = $_POST["priority_level"];
    $format = $_POST["format"];

    // Retrieve email from session
    $email = $_SESSION["email"] ?? null; // Assuming email is stored in the session

    if (!$email) {
        echo "<script>alert('Unable to retrieve user email. Please log in again.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }

    // Validate pages limit
    if ($pages > 1500) {
        echo "<script>alert('The maximum number of pages is 1499.');</script>";
    } else {
        // Handle file upload if files are provided
        $file_names_str = ''; // Initialize an empty string for file names

        if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"][0])) {
            $file_names = []; // Array to hold the names of the uploaded files

            // Loop through each uploaded file
            foreach ($_FILES["file"]["name"] as $index => $file_name) {
                $file_tmp = $_FILES["file"]["tmp_name"][$index];

                // Replace spaces with underscores and sanitize the filename
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = preg_replace("/[^A-Za-z0-9_\-\.]/", "", $file_name); // Remove special characters

                // Move the file to the uploads directory
                if (move_uploaded_file($file_tmp, "assignmentuploads/" . $file_name)) {
                    // Add the file name to the array
                    $file_names[] = $file_name;
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Error uploading file: " . $file_name,
                    ]);
                    exit();
                }
            }

            // Combine the file names into a single string, separated by '@@@'
            $file_names_str = implode("@@@", $file_names);
        }

        // Prepare the SQL statement to insert the assignment
        $sql = "INSERT INTO assignments (email, deadline, time, pages, subject, topic, resources, instructions, format, file, priority_level, submitted_on) 
                VALUES ('$email', '$deadline', '$time', '$pages', '$subject', '$topic', '$resources', '$instructions', '$format', '$file_names_str', '$priority_level', NOW())";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Assignment submitted successfully.');</script>";
            echo "<script>window.location.href = 'assignments_list.php';</script>";
        } else {
            $error = mysqli_error($con); // Get MySQL error
            echo "<script>alert('Error: " . addslashes($error) . "');</script>";
        }
    }
}


// Check if del and id are set
if (isset($_POST["del"]) && isset($_POST["id"])) {
    // Change to POST
    $id = mysqli_real_escape_string($con, $_POST["id"]); // Sanitize input
    // Debug output
    echo "Deleting assignment with ID: " . $id;

    $query = mysqli_query($con, "DELETE FROM assignments WHERE id = '$id'");

    if ($query) {
        $_SESSION["delmsg"] = "Assignment deleted !!";
        echo json_encode([
            "status" => "success",
            "message" => "Deletion successful.",
        ]);
    } else {
        $_SESSION["delmsg"] =
            "Error deleting assignment: " . mysqli_error($con);
        echo json_encode([
            "status" => "error",
            "message" => "Deletion failed: " . mysqli_error($con),
        ]);
    }
    exit(); // Prevent further output
}
?>

<!DOCTYPE html>
<html>
<?php @include "includes/head.php"; ?>
<body class="hold-transition sidebar-mini">
    
<div class="wrapper">
    <!-- Navbar -->
    <?php @include "includes/header.php"; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php @include "includes/sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assignment Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Manage Assignments</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Manage Assignments</h3>
                                <div class="card-tools">
                                    <!-- Button to open modal for adding a new assignment -->
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAssignmentModal"><span style="color: #fff;"><i class="fas fa-plus"></i> New Assignment</span></button>
                                </div>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body mt-2">
                                <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Deadline</th>
                                        <th>Subject</th>
                                        <th>Topic</th>
                                        <th>Pages</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
<?php
// Get the logged-in user's email and permission from the session
$email = $_SESSION["email"] ?? null;
$permission = $_SESSION["permission"] ?? null;

if ($email) {
    // Check if the user has 'Admin' or 'Super User' permission
    if ($permission === "Admin" || $permission === "Super User") {
        // If the user is an admin or super user, show all assignments
        $query = mysqli_query($con, "SELECT * FROM assignments");
    } else {
        // Otherwise, filter by the logged-in user's email
        $query = mysqli_query(
            $con,
            "SELECT * FROM assignments WHERE email = '$email'"
        );
    }

    // Counter for the row number
    $cnt = 1;

    // Fetch and display assignments
    while ($row = mysqli_fetch_array($query)) { ?>
        <tr>
            <td><?php echo htmlentities($cnt); ?></td>
            <td><?php echo htmlentities($row["deadline"]); ?></td>
            <td><?php echo htmlentities($row["subject"]); ?></td>
            <td><?php echo htmlentities($row["topic"]); ?></td>
            <td><?php echo htmlentities($row["pages"]); ?></td>
            <td>
                <button class="btn btn-info btn-xs view_data" id="<?php echo $row[
                    "id"
                ]; ?>">View</button>
                <button class="btn btn-danger btn-xs delete_data" id="<?php echo $row[
                    "id"
                ]; ?>">Delete</button>
            </td>
        </tr>
        <?php $cnt++;}
} else {
    echo "<tr><td colspan='6'>No assignments available or user not logged in.</td></tr>";
}
?>
</tbody>


                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->






       <!-- Modal for adding new assignment -->
    <div class="modal fade" id="addAssignmentModal" tabindex="-1" role="dialog" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="addAssignmentModalLabel" style="font-size: 1.5rem; font-weight: bold; color: #5A5A5A; text-align: center; padding: 10px 0;"> Add New Assignment</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-family: Arial, sans-serif; padding: 20px;">
                <form method="post" enctype="multipart/form-data" action="" id="assignmentForm">
                        <input type="hidden" name="submit_assignment" value="1">
                    <!-- Subject and Topic -->
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select class="form-control" id="subject" name="subject" required style="margin-bottom: 10px;">
                            <option value="" disabled selected>Select a subject</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Biology">Biology</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Physics">Physics</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Engineering">Engineering</option>
                            <option value="History">History</option>
                            <option value="Psychology">Psychology</option>
                            <option value="Sociology">Sociology</option>
                            <option value="Literature">Literature</option>
                            <option value="Economics">Economics</option>
                            <option value="Political Science">Political Science</option>
                            <option value="Business Studies">Business Studies</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="topic">Topic</label>
                        <input type="text" class="form-control" id="topic" name="topic" required style="margin-bottom: 10px;">
                    </div>

                    <!-- Instructions -->
                    <div class="form-group">
                        <label for="instructions">Instructions</label>
                        <textarea class="form-control" id="instructions" name="instructions" required style="margin-bottom: 10px;"></textarea>
                    </div>

                    <!-- Attachment -->
                    <div class="form-group">
                        <label for="file">Assignment File</label>
                        <input type="file" class="form-control" id="file" multiple name="file[]" accept=".pdf,.doc,.docx" style="margin-bottom: 10px;">
                    </div>

                    <!-- Sources, Number of Pages, Deadline and Time -->
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label for="resources">Resources</label>
                            <input type="number" class="form-control" id="resources" name="resources" required value="1">

                        </div>
                        <div class="col-md-4">
                            <label for="pages">Pages</label>
                            <input type="number" value="1" class="form-control" id="pages" name="pages" required max="150">
                        </div>
                        <div class="col-md-4">
                            <label for="deadline">Deadline</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required min="<?php echo date(
                                "Y-m-d"
                            ); ?>">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
    <div class="col-md-4">
        <label for="time">Time</label>
        <input type="time" class="form-control" id="time" name="time" required  ?>">
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="priority_level">Priority Level</label>
            <select class="form-control" id="priority_level" name="priority_level" required style="margin-bottom: 10px;">
                <option value="" disabled selected>Select priority level</option>
                <option value="Urgent">Urgent</option>
                <option value="Very Urgent">Very Urgent</option>
                <option value="Normal">Normal</option>
            </select>
        </div>
    </div>

    <!-- New dropdown for format -->
    <div class="col-md-4">
        <div class="form-group">
            <label for="format">Format</label>
            <select class="form-control" id="format" name="format" required style="margin-bottom: 10px;">
                <option value="" disabled selected>Select format</option>
                <option value="APA">APA</option>
                <option value="MLA">MLA</option>
                <option value="Chicago">Chicago</option>
                <option value="Harvard">Harvard</option>
            </select>
        </div>
    </div>
</div>

                    </div>

                    <!-- Priority Level -->
                  
           <div class="modal-footer d-flex justify-content-between">
               <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 48%;">Cancel</button>
               <button type="submit" class="btn btn-primary" id="submitAssignmentBtn" style="width: 48%;">Submit</button>
</div>

                </form>
            </div>

        </div>
    </div>
</div>


    <!-- Modal for Viewing Assignment Details -->
    <div id="viewData" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title">Assignment Details</h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="info_view">
                    <!-- Assignment details will be loaded here -->
                     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <?php @include "includes/footer.php"; ?>
</div>

<!-- ./wrapper -->
<?php @include "includes/foot.php"; ?>

<script type="text/javascript">
    $(document).ready(function () {
        // View assignment details
        $(document).on('click', '.view_data', function () {
            var view_id = $(this).attr('id');
            $.ajax({
                url: "view_assignment.php",
                type: "post",
                data: { view_id: view_id },
                success: function (data) {
                    $("#info_view").html(data);
                    $("#viewData").modal('show');
                }
            });
        });

            // Save changes (mark as done, attach file, and add comments)
        $(document).on('click', '#save_changes', function () {
            var assignment_id = $(this).data('id');
            var is_done = $('#work_done').is(':checked') ? 1 : 0;
            var file_data = $('#work_results').prop('files')[0]; // Get the file
            var comments = $('#comments').val(); // Get the comments from the textarea

            var formData = new FormData();
            formData.append('id', assignment_id);
            formData.append('work_is_done', is_done);
            formData.append('work_results', file_data); // Append the file
            formData.append('comments', comments); // Append the comments

            $.ajax({
                url: "update_assignment.php", // Create this file to handle updates
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    alert("Changes saved successfully.");
                    $("#viewData").modal('hide'); // Close the modal
                    location.reload(); // Reload the page
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                }
            });
        });


        // Delete assignment
        $(document).on('click', '#delete_assignment', function () {
            var delete_id = $(this).data('id');
            if (confirm('Are you sure you want to delete this assignment?')) {
                $.ajax({
                    url: "assignment_list.php",
                    type: "post",
                    data: { del: 1, id: delete_id },
                    success: function (response) {
                        console.log(response);
                        try {
                            var res = JSON.parse(response);
                            if (res.status === "success") {
                                alert(res.message);
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            console.error("Failed to parse response:", e);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                    }
                });
            }
        });
        

    });
</script>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
  (function () {
    var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/673e09122480f5b4f5a11e8e/1id556sjd';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
  })();
</script>
<!--End of Tawk.to Script-->




  <!-- SweetAlert2 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.all.min.js"></script>
</body>
</html>


