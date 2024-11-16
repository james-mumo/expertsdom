<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1); // Display errors on the screen
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
}

// Check if del and id are set
if (isset($_POST['del']) && isset($_POST['id'])) { // Change to POST
    $id = mysqli_real_escape_string($con, $_POST['id']); // Sanitize input
    // Debug output
    echo "Deleting assignment with ID: " . $id;

    $query = mysqli_query($con, "DELETE FROM assignments WHERE id = '$id'");

    if ($query) {
        $_SESSION['delmsg'] = "Assignment deleted !!";
        echo json_encode(["status" => "success", "message" => "Deletion successful."]);
    } else {
        $_SESSION['delmsg'] = "Error deleting assignment: " . mysqli_error($con);
        echo json_encode(["status" => "error", "message" => "Deletion failed: " . mysqli_error($con)]);
    }
    exit(); // Prevent further output
}
?>

<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <?php @include("includes/header.php"); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php @include("includes/sidebar.php"); ?>

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
                                    <a href="add_assignment.php"><button type="button" class="btn btn-sm btn-primary"><span style="color: #fff;"><i class="fas fa-plus"></i> New Assignment</span></button></a>
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
                                    $query = mysqli_query($con, "SELECT * FROM assignments");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($row['deadline']); ?></td>
                                            <td><?php echo htmlentities($row['subject']); ?></td>
                                            <td><?php echo htmlentities($row['topic']); ?></td>
                                            <td><?php echo htmlentities($row['pages']); ?></td>
                                            <td>
                                                <button class="btn btn-info btn-xs view_data" id="<?php echo $row['id']; ?>">View</button>
                                                <button class="btn btn-danger btn-xs delete_data" id="<?php echo $row['id']; ?>">Delete</button>
                                            </td>
                                        </tr>
                                        <?php $cnt++;
                                    } ?>
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

    <!-- Modal for Viewing Assignment Details -->
    <div id="viewData" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assignment Details</h5>
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

    <?php @include("includes/footer.php"); ?>
</div>

<!-- ./wrapper -->
<?php @include("includes/foot.php"); ?>

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

        // Save changes (mark as done and attach file)
        $(document).on('click', '#save_changes', function () {
            var assignment_id = $(this).data('id');
            var is_done = $('#work_done').is(':checked') ? 1 : 0;
            var file_data = $('#work_results').prop('files')[0]; // Get the file

            var formData = new FormData();
            formData.append('id', assignment_id);
            formData.append('work_is_done', is_done);
            formData.append('work_results', file_data); // Append the file

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

</body>
</html>
