<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}

?>
<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php @include("includes/header.php"); ?>
        <!-- Main Sidebar Container -->
        <?php @include("includes/sidebar.php"); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">Home</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <a href="assignments_list.php">
                                <div class="small-box bg-success">
                                    <?php
                                    $query1 = mysqli_query($con, "SELECT * FROM assignments");
                                    $totalcust = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $totalcust; ?></h3>
                                        <p>Total Assignments</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <div class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <a href="student_list.php">
                                <div class="small-box bg-info">
                                    <?php
                                    $query1 = mysqli_query($con, "SELECT * FROM tblusers");
                                    $totalcust = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $totalcust; ?></h3>
                                        <p>Total Students</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <div class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <!-- ./col -->
                        <!-- <div class="col-lg-4 col-6">
                            
                            <div class="small-box bg-success">
                                <?php $query2 = mysqli_query($con, "Select * from students where gender='Male'");
                                $totalmale = mysqli_num_rows($query2);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalmale; ?></h3>

                                    <p>Total Male students</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div> -->
                        <!-- ./col -->
                        <!-- <div class="col-lg-4 col-6">
                                                        <div class="small-box bg-info">
                                <?php $query3 = mysqli_query($con, "Select * from students where gender='Female'");
                                $totalfemale = mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale; ?></h3>

                                    <p>Total female students</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div> -->
                        <!-- ./col -->
                        <!-- ./col -->
                    </div>
                </div>
                <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php @include("includes/footer.php"); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
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