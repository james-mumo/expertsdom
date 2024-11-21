<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

$message = ""; // Variable to store messages

if (isset($_POST['signup'])) {
    // Get form data
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Ensure password is hashed
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $sex = $_POST['sex'] ?? 'Not specified'; // Assuming default value if not provided
    $permission = 'User'; // Default permission
    $mobile = $_POST['mobile'] ?? ''; // Optional mobile field
    $userimage = 'default.jpg'; // Default user image

    // Check if the username already exists
    $sql = "SELECT * FROM tblusers WHERE username = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $message = 'Username already exists. Please choose another.';
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO tblusers (name, lastname, username, email, sex, permission, password, mobile, userimage, status) 
                VALUES (:name, :lastname, :username, :email, :sex, :permission, :password, :mobile, :userimage, :status)";
        $query = $dbh->prepare($sql);
        
        $status = 1; // Set default status as active (1)
        
        // Bind parameters
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':sex', $sex, PDO::PARAM_STR);
        $query->bindParam(':permission', $permission, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':userimage', $userimage, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);

        if ($query->execute()) {
            // Redirect with a success message
            $_SESSION['message'] = 'Signup successful! You can now login.';
            header('Location: login.php'); // Redirect to login page
            exit();
        } else {
            $message = 'Signup failed. Please try again later.';
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
                    <p><b>Signup</b></p>
                    <center><img src="img/globe.png" width="150" height="130" class="user-image" alt="User Image"/></center>
                </div>
                <p class="login-box-msg"><b><h4><center>Sign Up</center></h4></b></p>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

           <form action="" method="post">
    <div class="row mb-3">
        <div class="col">
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="First Name" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="input-group">
                <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="number" name="mobile" class="form-control" placeholder="Mobile" optional>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <select name="sex" class="form-control">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <div class="row">
        <div class="col-4">
            <button type="submit" name="signup" class="btn btn-primary btn-block">Sign Up</button>
        </div>
    </div>
</form>


                <div class="text-center">
                    <p class="mb-1">
                        <a href="login.php">Already have an account? Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php @include("includes/foot.php"); ?>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/673e09122480f5b4f5a11e8e/1id556sjd';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
