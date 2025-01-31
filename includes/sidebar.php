<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="dashboard.php" class="brand-link">
    <img src="img/globe.png" alt="Leading Estate" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Expertsdom</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <?php
      // Fetch user details based on the session user ID
      $eid = $_SESSION['sid'];
      $sql = "SELECT * FROM tblusers WHERE id = :eid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':eid', $eid, PDO::PARAM_STR);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);

      if ($query->rowCount() > 0) {
        foreach ($results as $row) {
          $name = htmlentities($row->name);
          $lastName = htmlentities($row->lastname);
          $permission = htmlentities($row->permission);  // User permission (Admin, User, etc.)
          $userImage = htmlentities($row->userimage);
      ?>

          <div class="image" style="background-color: white; padding: 5px; border-radius: 50%;">
            <img class="img-circle" src="staff_images/prof.png" width="90px" height="90px" alt="User profile picture">
          </div>

          <div class="info">
            <a href="profile.php" class="d-block"><?php echo $name . ' ' . $lastName; ?></a>
            <!-- Display the user permission -->
          </div>

      <?php
        }
      }
      ?>

    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview menu-open">
          <a href="dashboard.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Show Add Student only for Admin and Super User -->
        <!--  -->
        <!-- <li class="nav-item">
          <a href="paypal_checkout.php" class="nav-link">
            <i class="nav-icon far fa-plus-square"></i>
            <p>Checkout</p>
          </a>
        </li> -->


        <!-- Show Manage Students only for Admin and Super User -->
        <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>
          <li class="nav-item">
            <a href="student_list.php" class="nav-link">
              <i class="nav-icon far fa-user"></i>
              <p>Manage Students</p>
            </a>
          </li>
        <?php } ?>

        <!-- Show List Assignments for all permissions -->
        <li class="nav-item">
          <a href="assignments_list.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
            <p>List Assignments</p>
          </a>
        </li>

        <!-- <li class="nav-header">SETTINGS & SECURITY</li> -->

        <!-- Show Register User only for Admin and Super User -->
        <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>
          <li class="nav-item">
            <a href="userregister.php" class="nav-link">
              <i class="far fa-user nav-icon"></i>
              <p>Register User</p>
            </a>
          </li>
        <?php } ?>

        <!-- Show Audit Logs for Admin and Super User -->
        <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>
          <li class="nav-item">
            <a href="blogs.php" class="nav-link">
              <i class="far fa-image nav-icon"></i>
              <p>Blogs</p>
            </a>
          </li>
        <?php } ?>



        <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>
          <li class="nav-item">
            <a href="sample_upload.php" class="nav-link">
              <i class="far fa-image nav-icon"></i>
              <p>Samples</p>
            </a>
          </li>
        <?php } ?>

        <li class="nav-item">
          <a href="profile.php" class="nav-link">
            <i class="fas fa-envelope nav-icon"></i>
            <p> profile </p>
          </a>
        </li>

        <!-- <li class="nav-item">
          <a href="blogs.php" class="nav-link">
            <i class="far fa-image nav-icon"></i>
            <p> Blogs </p>
          </a>
        </li> -->


        <li class="nav-item">
          <a href="changepassword.php" class="nav-link">
            <i class="fas fa-users mr-2"></i>
            <p> settings </p>
          </a>
        </li>


        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="fas fa-file nav-icon"></i>
            <p> logout </p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>