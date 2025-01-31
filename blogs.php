<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include('includes/dbconnection.php');




if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $blog_type = $_POST['blog_type']; // Capture selected blog type
        $title = $_POST['title'];
        $subheading1 = $_POST['subheading1'];
        $subcontent1 = $_POST['subcontent1'];
        $subheading2 = $_POST['subheading2'];
        $subcontent2 = $_POST['subcontent2'];
        $subheading3 = $_POST['subheading3'];
        $subcontent3 = $_POST['subcontent3'];
        $conclusion = $_POST['conclusion'];

        $sql = "INSERT INTO blogs (blog_type, title, subheading1, subcontent1, subheading2, subcontent2, subheading3, subcontent3, conclusion) 
            VALUES (:blog_type, :title, :subheading1, :subcontent1, :subheading2, :subcontent2, :subheading3, :subcontent3, :conclusion)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':blog_type', $blog_type, PDO::PARAM_STR);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':subheading1', $subheading1, PDO::PARAM_STR);
        $query->bindParam(':subcontent1', $subcontent1, PDO::PARAM_STR);
        $query->bindParam(':subheading2', $subheading2, PDO::PARAM_STR);
        $query->bindParam(':subcontent2', $subcontent2, PDO::PARAM_STR);
        $query->bindParam(':subheading3', $subheading3, PDO::PARAM_STR);
        $query->bindParam(':subcontent3', $subcontent3, PDO::PARAM_STR);
        $query->bindParam(':conclusion', $conclusion, PDO::PARAM_STR);

        if ($query->execute()) {
            echo '<script>alert("Blog added successfully!")</script>';
            echo "<script>window.location.href ='blogs.php'</script>";
        } else {
            echo '<script>alert("Error adding blog. Please try again.")</script>';
        }
    }


    // Delete Blog Logic
    if (isset($_POST['delete'])) {
        $delete_id = $_POST['delete_id'];

        $sql = "DELETE FROM blogs WHERE id = :delete_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);

        if ($query->execute()) {
            echo '<script>alert("Blog deleted successfully!");</script>';
            echo "<script>window.location.href ='blogs.php'</script>";
        } else {
            echo '<script>alert("Error deleting blog. Please try again.");</script>';
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
                            <h6 class="m-0 font-weight-bold text-primary">Manage Blogs</h6>
                        </div>
                        <div class="card-body">
                            <form method="post">

                                <div class="form-group">
                                    <label for="blog_type">Blog Type</label>
                                    <select class="form-control" name="blog_type" required>
                                        <option value="">Select Blog Type</option>
                                        <option value="Guides">Guides</option>
                                        <option value="Samples">Samples</option>
                                        <option value="Tips">Tips</option>
                                        <option value="Topics">Topics</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="title">Blog Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>

                                <!-- Subheading 1 -->
                                <div class="form-group">
                                    <label for="subheading1">Subheading 1</label>
                                    <input type="text" class="form-control" name="subheading1" required>
                                </div>
                                <div class="form-group">
                                    <label for="subcontent1">Content for Subheading 1</label>
                                    <textarea class="form-control" name="subcontent1" rows="3" required></textarea>
                                </div>

                                <!-- Subheading 2 -->
                                <div class="form-group">
                                    <label for="subheading2">Subheading 2</label>
                                    <input type="text" class="form-control" name="subheading2" required>
                                </div>
                                <div class="form-group">
                                    <label for="subcontent2">Content for Subheading 2</label>
                                    <textarea class="form-control" name="subcontent2" rows="3" required></textarea>
                                </div>

                                <!-- Subheading 3 -->
                                <div class="form-group">
                                    <label for="subheading3">Subheading 3</label>
                                    <input type="text" class="form-control" name="subheading3" required>
                                </div>
                                <div class="form-group">
                                    <label for="subcontent3">Content for Subheading 3</label>
                                    <textarea class="form-control" name="subcontent3" rows="3" required></textarea>
                                </div>

                                <!-- Conclusion -->
                                <div class="form-group">
                                    <label for="conclusion">Conclusion</label>
                                    <textarea class="form-control" name="conclusion" rows="3" required></textarea>
                                </div>

                                <button class="btn btn-primary" type="submit" name="submit">Add Blog</button>
                            </form>
                        </div>
                    </div>

                    <!-- Display Existing Blogs -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Existing Blogs</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th> <!-- New Column for Blog Type -->
                                        <th>Title</th>
                                        <th>Subheading 1</th>
                                        <th>Subheading 2</th>
                                        <th>Subheading 3</th>
                                        <th>Conclusion</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM blogs";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($row->blog_type); ?></td> <!-- Display Blog Type -->
                                                <td><?php echo htmlentities($row->title); ?></td>
                                                <td><?php echo htmlentities($row->subheading1); ?></td>
                                                <td><?php echo htmlentities($row->subheading2); ?></td>
                                                <td><?php echo htmlentities($row->subheading3); ?></td>
                                                <td><?php echo substr(htmlentities($row->conclusion), 0, 100) . '...'; ?></td>
                                                <td>
                                                    <form method="post" action="blogs.php">
                                                        <input type="hidden" name="delete_id" value="<?php echo $row->id; ?>">
                                                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this blog?');">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        <?php $cnt++;
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8">No blogs found</td>
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