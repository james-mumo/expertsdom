<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
{
  $username=$_POST['username'];
  $password=md5($_POST['password']);
  $sql ="SELECT * FROM tblusers WHERE username=:username and Password=:password ";
  $query=$dbh->prepare($sql);
  $query-> bindParam(':username', $username, PDO::PARAM_STR);
  $query-> bindParam(':password', $password, PDO::PARAM_STR);
  $query-> execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach ($results as $result) {
      $_SESSION['sid']=$result->id;
      $_SESSION['name']=$result->name;
      $_SESSION['lastname']=$result->lastname;
      $_SESSION['permission']=$result->permission;
      $_SESSION['email']=$result->email;
    }

    if(!empty($_POST["remember"])) {
      // COOKIES for username
      setcookie ("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60));
      // COOKIES for password
      setcookie ("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
    } else {
      if(isset($_COOKIE["user_login"])) {
        setcookie ("user_login", "");
        if(isset($_COOKIE["userpassword"])) {
          setcookie ("userpassword", "");
        }
      }
    }

    $aa = $_SESSION['sid'];
    $sql = "SELECT * FROM tblusers WHERE id=:aa";
    $query = $dbh->prepare($sql);
    $query->bindParam(':aa', $aa, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0)
    {
      foreach($results as $row)
      {               
        if($row->status == "1") { 
          $extra = "dashboard.php";
          $_SESSION['login'] = $_POST['username'];
          $_SESSION['id'] = $row->id;
          $_SESSION['username'] = $row->name;
          $uip = $_SERVER['REMOTE_ADDR'];
          $status = 1;
          $sql = "INSERT INTO userlog(userEmail, userip, status, username, name, lastname) 
                  VALUES(:email, :uip, :status, :username, :name, :lastname)";
          $query = $dbh->prepare($sql);
          $query->bindParam(':username', $username, PDO::PARAM_STR);
          $query->bindParam(':name', $row->name, PDO::PARAM_STR);
          $query->bindParam(':lastname', $row->lastname, PDO::PARAM_STR);
          $query->bindParam(':email', $row->email, PDO::PARAM_STR);
          $query->bindParam(':uip', $uip, PDO::PARAM_STR);
          $query->bindParam(':status', $status, PDO::PARAM_STR);
          $query->execute();
          
          // Redirect to login.php with success flag
          header("Location: login.php?success=true");
          exit();
        } else { 
          // User account is blocked
          echo "<script>alert('Your account was blocked, please approach Admin'); document.location ='login.php';</script>";                                        
        }
      }
    } else {
      $_SESSION['error_message'] = 'Username or Password is incorrect';
      header('Location: login.php');
      exit();
    }
  }
}
?>

<?php @include("includes/head.php"); ?>
<body class="hold-transition login-page">
  <div class="login-box">  
    <div class="card">
      <div class="card-body login-card-body">
        <div class="login-logo">
          <center><img src="img/globe.png" width="150" height="130" class="user-image" alt="User Image"/></center>
        </div>
        <p class="login-box-msg"><b> <h4> <center> Welcome </center></h4> </b></p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember"  <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </div>
          </div>
        </form>

        <div class="text-center">
          <p class="mb-1">
              <a href="signup.php">Proceed to Signup Instead</a>
          </p>
        </div>

      </div>
    </div>
  </div>
  
  <?php @include("includes/foot.php"); ?>

  <!-- SweetAlert2 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.all.min.js"></script>

  <script>
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Login Successful!',
        text: 'Welcome to the dashboard.',
        showConfirmButton: false,
                    timer: 2000
      }).then(() => {
        window.location.href = 'dashboard.php'; // Redirect to dashboard after alert
      });
    <?php endif; ?>
  </script>

  <?php if (isset($_SESSION['error_message'])): ?>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?php echo $_SESSION['error_message']; ?>',
        showConfirmButton: true,
      });
      <?php unset($_SESSION['error_message']); ?> // Clear error message after alert
    <?php endif; ?> 

  <script src="assets/js/core/js.cookie.min.js"></script>




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
</body>
</html>
