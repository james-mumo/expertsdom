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
            // Fetch the newly inserted user data
            $sql = "SELECT * FROM tblusers WHERE username = :username LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);

            // Set session variables for user data
            $_SESSION['sid'] = $result->id;
            $_SESSION['name'] = $result->name;
            $_SESSION['lastname'] = $result->lastname;
            $_SESSION['permission'] = $result->permission;
            $_SESSION['email'] = $result->email;

            // Set success message in session
            $_SESSION['message'] = 'Signup successful!!!.';
            
            // Redirect to the dashboard after signup (delayed by 3 seconds)
            header("Location: signup.php?success=true");
            exit();
        } else {
            $message = 'Signup failed. Please try again later.';
        }
    }
}
?>

<?php @include("includes/head.php"); ?>

<!-- SweetAlert2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.all.min.js"></script>

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

<form action="" method="post" id="signupForm">
    <!-- Form inputs here -->
    <div class="row mb-3">
        <div class="col-6">
            <input type="text" name="name" class="form-control" placeholder="First Name" required>
        </div>
        <div class="col-6">
            <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
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
        <input type="number" name="mobile" class="form-control" placeholder="Mobile" optional maxlength="12" minlength="12">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-phone-alt"></span>
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
    
    <div class="row d-flex justify-content-center">
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

    <!-- Loader HTML (hidden by default) -->
    <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <?php @include("includes/foot.php"); ?>

    <!-- SweetAlert2 Script for Signup Success -->
    <script>
        // Set the alert message from PHP
        var alertMessage = "<?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?>";

        if (alertMessage !== "") {
            // Show the loader when processing
            document.getElementById("loader").style.display = "block";

            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: alertMessage,
                    text: 'Redirecting to the dashboard...',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'dashboard.php'; // Redirect to the dashboard
                });

                // Hide the loader
                document.getElementById("loader").style.display = "none";

                // Clear the session message after the alert
                <?php unset($_SESSION['message']); ?>
            }, 1000); // Delay for 3 seconds before showing the alert and redirecting
        }
    </script>

</body>
</html>
