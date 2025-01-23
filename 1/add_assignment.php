<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
    exit; // Ensure script stops after redirection
}

if (isset($_POST['submit'])) {
    // Collect form data
    $deadline = $_POST['deadline'];
    $time = $_POST['time'];
    $pages = $_POST['pages'];
    $subject = $_POST['subject'];
    $topic = $_POST['topic'];
    $resources = $_POST['resources'];
    $format = $_POST['format'];
    $instructions = $_POST['instructions'];
    $priority_level = $_POST['priority_level'];

    // Validate pages limit
    if ($pages > 150) {
        echo "<script>alert('The maximum number of pages is 150.');</script>";
    } else {
        // Handle assignment file upload
        $file = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "assignmentuploads/" . $file);

        // Handle work_results file upload (optional)
        $work_results = NULL; // Default to NULL
        if (isset($_FILES["work_results"]) && $_FILES["work_results"]["error"] == UPLOAD_ERR_OK) {
            $work_results = $_FILES["work_results"]["name"];
            move_uploaded_file($_FILES["work_results"]["tmp_name"], "assignmentuploads/" . $work_results);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO assignments (deadline, time, pages, subject, topic, resources, format, instructions, file, work_results, work_is_done, priority_level) 
                VALUES ('$deadline', '$time', '$pages', '$subject', '$topic', '$resources', '$format', '$instructions', '$file', " . ($work_results ? "'$work_results'" : "NULL") . ", NULL, '$priority_level')";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Assignment details submitted successfully.');</script>"; 
            echo "<script>window.location.href = 'assignments_list.php';</script>";   
        } else {
            $error = mysqli_error($con); // Get the MySQL error
            echo "<script>alert('Something went wrong: " . addslashes($error) . "');</script>";    
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/head.php"); ?>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include("includes/header.php"); ?>
        <?php include("includes/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Assignment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Add Assignment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <form method="post" enctype="multipart/form-data" id="assignmentForm">
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" class="form-control" id="time" name="time" required min="<?php echo date('H:i'); ?>">
                </div>
                <div class="form-group">
                    <label for="pages">Pages</label>
                    <input type="number" class="form-control" id="pages" name="pages" required max="150">
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
                    <label for="resources">Resources</label>
                    <input type="text" class="form-control" id="resources" name="resources" required>
                </div>
                <div class="form-group">
                    <label for="format">Format</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="" disabled selected>Select a format</option>
                        <option value="APA">APA</option>
                        <option value="MLA">MLA</option>
                        <option value="Harvard">Harvard</option>
                        <option value="Chicago">Chicago</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea class="form-control" id="instructions" name="instructions" required></textarea>
                </div>
                <div class="form-group">
                    <label for="priority_level">Priority Level</label>
                    <select class="form-control" id="priority_level" name="priority_level" required>
                        <option value="" disabled selected>Select priority level</option>
                        <option value="Urgent">Urgent</option>
                        <option value="Very Urgent">Very Urgent</option>
                        <option value="Normal">Normal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="file">Assignment File</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx" >
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <?php include("includes/footer.php"); ?>
    </div>
    <?php include("includes/foot.php"); ?>

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
