<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        // Process payment or other actions here
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
                                <h3>Paypal Payment</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                    <li class="breadcrumb-item active">Payment</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Order Details Table -->
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Order Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_SESSION['assignment_details'])) {
                                                    $assignment = $_SESSION['assignment_details'];
                                                    echo "<tr><td>Subject</td><td>" . $assignment['subject'] . "</td></tr>";
                                                    echo "<tr><td>Topic</td><td>" . $assignment['topic'] . "</td></tr>";
                                                    echo "<tr><td>Instructions</td><td>" . $assignment['instructions'] . "</td></tr>";
                                                    echo "<tr><td>Resources</td><td>" . $assignment['resources'] . "</td></tr>";
                                                    echo "<tr><td>Pages</td><td>" . $assignment['pages'] . "</td></tr>";
                                                    echo "<tr><td>Deadline</td><td>" . $assignment['deadline'] . "</td></tr>";
                                                    echo "<tr><td>Amount</td><td>$" . $assignment['amount'] . "</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal Checkout Details Table -->
                            <div class="col-md-6">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">PayPal Checkout Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>First Name</td>
                                                    <td><input type="text" class="form-control" name="fname" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Last Name</td>
                                                    <td><input type="text" class="form-control" name="lname" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><input type="email" class="form-control" name="email" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Address</td>
                                                    <td><input type="text" class="form-control" name="address" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><input type="text" class="form-control" name="phone" required></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php @include("includes/footer.php"); ?>

        </div>

        <!-- ./wrapper -->
        <?php @include("includes/foot.php"); ?>

        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/673e09122480f5b4f5a11e8e/1id556sjd';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    </body>

    </html>
<?php
} ?>