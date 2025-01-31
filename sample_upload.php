<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $topic = $_POST['topic'];

        // File upload logic
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $file_name = $_FILES['pdf_file']['name'];
            $file_tmp = $_FILES['pdf_file']['tmp_name'];
            $file_size = $_FILES['pdf_file']['size'];
            $file_type = $_FILES['pdf_file']['type'];

            // Set file upload directory
            $upload_dir = 'uploads/samples/';
            $file_path = $upload_dir . basename($file_name);

            // Check if the file is a PDF
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($file_extension != 'pdf') {
                echo '<script>alert("Only PDF files are allowed.")</script>';
            } else {
                // Move uploaded file to the server directory
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Insert into database
                    $sql = "INSERT INTO samples (title, topic, file_path) 
                            VALUES (:title, :topic, :file_path)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':title', $title, PDO::PARAM_STR);
                    $query->bindParam(':topic', $topic, PDO::PARAM_STR);
                    $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);

                    if ($query->execute()) {
                        echo '<script>alert("Sample uploaded successfully!")</script>';
                    } else {
                        echo '<script>alert("Error uploading sample. Please try again.")</script>';
                    }
                } else {
                    echo '<script>alert("Error moving the uploaded file. Please try again.")</script>';
                }
            }
        }
    }
?>

    <?php @include("includes/head.php"); ?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Navbar -->
            <?php @include("includes/header.php"); ?>
            <!-- Sidebar -->
            <?php @include("includes/sidebar.php"); ?>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <br>
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Upload Sample</h6>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="topic">Topic</label>
                                    <input type="text" class="form-control" name="topic" required>
                                </div>

                                <div class="form-group">
                                    <label for="pdf_file">Upload PDF</label>
                                    <input type="file" class="form-control" name="pdf_file" accept="application/pdf" required>
                                </div>

                                <button class="btn btn-primary" type="submit" name="submit">Upload Sample</button>
                            </form>
                        </div>
                    </div>

                    <!-- Display Existing Samples -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Existing Samples</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Topic</th>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM samples";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($row->title); ?></td>
                                                <td><?php echo htmlentities($row->topic); ?></td>
                                                <td><a href="<?php echo htmlentities($row->file_path); ?>" target="_blank">View PDF</a></td>
                                                <td>
                                                    <form method="post" action="sample_upload.php">
                                                        <input type="hidden" name="delete_id" value="<?php echo $row->id; ?>">
                                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this sample?');">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php $cnt++;
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5">No samples found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.content-wrapper -->

            <?php @include("includes/foot.php"); ?>
            <?php @include("includes/footer.php"); ?>
        </div>

    </body>

    </html>
<?php } ?>