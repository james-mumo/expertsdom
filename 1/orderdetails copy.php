<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/dbconnection.php');

// Check if session is active and user ID exists
$user_id = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;

if ($user_id) {
    // Assuming 'users' table stores user information
    $query = "SELECT email FROM users WHERE id = '$user_id'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_email = $user['email'];
    } else {
        // If user data isn't found, redirect to logout
        header('location:logout.php');
        exit;
    }
} else {
    // If session not set, no user is logged in
    $user_email = null;
}

// Assignment form submission logic
if (isset($_POST['submit'])) {
    $deadline = $_POST['deadline'];
    $time = $_POST['time'];
    $pages = $_POST['pages'];
    $subject = $_POST['subject'];
    $topic = $_POST['topic'];
    $resources = $_POST['resources'];
    $format = $_POST['format'];
    $instructions = $_POST['instructions'];
    $priority_level = $_POST['priority_level'];
    $email = $user_id ? $user_email : $_POST['email'];  // Use session email or input email

    if ($pages > 150) {
        echo "<script>alert('The maximum number of pages is 150.');</script>";
    } else {
        $file = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "assignmentuploads/" . $file);

        $sql = "INSERT INTO assignments (email, deadline, time, pages, subject, topic, resources, format, instructions, file, priority_level) 
                VALUES ('$email', '$deadline', '$time', '$pages', '$subject', '$topic', '$resources', '$format', '$instructions', '$file', '$priority_level')";

        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Assignment details submitted successfully.');</script>"; 
            echo "<script>window.location.href = 'login.php';</script>";   
        } else {
            $error = mysqli_error($con);
            echo "<script>alert('Something went wrong: " . addslashes($error) . "');</script>";    
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Details</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="css/landing-page.min.css" rel="stylesheet" />

    <style>
        #brandText { font-weight: bold; font-size: 1.5rem; }
        .active-button { background-color: #007bff; color: white; }
        .inactive-button { background-color: white; color: black; }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light static-top">
        <div class="container">
            <a class="navbar-brand" href="index.html" id="brandText">Expertsdom</a>
            <div class="ml-auto d-flex align-items-center">
                <?php if ($user_id): ?>
                    <span class="nav-item nav-link">Welcome, <?php echo htmlspecialchars($user_email); ?></span>
                    <a class="nav-item nav-link" href="logout.php">Log Out</a>
                <?php else: ?>
                    <a class="nav-item nav-link" href="login.php">Log In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <form method="post" enctype="multipart/form-data" id="assignmentForm">
            <!-- Conditionally display email input if no user session -->
            <?php if (!$user_id): ?>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="pages">Pages</label>
                <input type="number" class="form-control" id="pages" name="pages" required min="1" max="150">
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <select class="form-control" id="subject" name="subject" required>
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
            </div>
            <div class="form-group">
                <label for="topic">Topic</label>
                <input type="text" class="form-control" id="topic" name="topic" required>
            </div>
            <div class="form-group">
                <label for="resources">Number of Cited Resources</label>
                <input type="number" class="form-control" id="resources" name="resources" required min="0">
            </div>
            <div class="form-group">
                <label for="format">Formatting Style</label>
                <select class="form-control" id="format" name="format" required>
                    <option value="" disabled selected>Select a format</option>
                    <option value="APA">APA</option>
                    <option value="MLA">MLA</option>
                    <option value="Harvard">Harvard</option>
                    <option value="Chicago">Chicago</option>
                </select>
            </div>
            <div class="form-group">
                <label for="instructions">Detailed Instructions</label>
                <textarea class="form-control" id="instructions" name="instructions" required></textarea>
            </div>
            <div class="form-group">
                <label for="priority_level">Priority Level</label>
                <select class="form-control" id="priority_level" name="priority_level" required>
                    <option value="" disabled selected>Select Priority Level</option>
                    <option value="Urgent">Urgent</option>
                    <option value="Very Urgent">Very Urgent</option>
                    <option value="Normal">Normal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">Assignment File</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <footer class="footer bg-light">
        <!-- Footer content as provided above -->
    </footer>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
