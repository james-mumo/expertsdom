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
    $query = "SELECT email FROM tblusers WHERE id = '$user_id'";
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
    $amount = $_POST["amount"];

    if ($pages > 150) {
        echo "<script>alert('The maximum number of pages is 150.');</script>";
    } else {
        $file = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "assignmentuploads/" . $file);

        $sql = "INSERT INTO assignments (email, deadline, time, pages, subject, topic, resources, format, instructions, file, priority_level, order_amount) 
                VALUES ('$email', '$deadline', '$time', '$pages', '$subject', '$topic', '$resources', '$format', '$instructions', '$file', '$priority_level', '$amount')";

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
    <title>Expertsdom - Order Details</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="css/landing-page.min.css" rel="stylesheet" />

    <style>
        #brandText {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .active-button {
            background-color: #007bff;
            color: white;
        }

        .inactive-button {
            background-color: white;
            color: black;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light static-top">
        <div class="container">
            <!-- Navbar Brand -->
            <a class="navbar-brand" href="index.html" id="brandText">Expertsdom</a>
            <!-- Toggler for Mobile View -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <div class="navbar-nav ml-auto">
                    <!-- Dropdown Menu -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="writingSamplesDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Writing Help
                        </a>
                        <div class="dropdown-menu" aria-labelledby="writingSamplesDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Essay
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Research
                                Paper
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Thesis
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal"
                                data-target="#essayWritingModal">Assignment
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Speech
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Book
                                Report Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Lab
                                Report Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Capstone
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Case
                                Study Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal"
                                data-target="#essayWritingModal">Literature Review
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Personal
                                Statement Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal"
                                data-target="#essayWritingModal">Coursework
                                Writing</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Homework
                                Writing</a>
                        </div>
                    </div>
                    <a class="nav-item nav-link" href="samples_view.php">Samples</a>
                    <a class="nav-item nav-link" href="blog.php">Blog</a>
                    <!-- <a class="btn btn-primary mx-2 my-1 btn-sign-in" href="orderdetails.php">Order Now</a> -->
                    <div class="ml-auto d-flex align-items-center">
                        <?php if ($user_id): ?>
                            <span class="nav-item nav-link">Welcome, <?php echo htmlspecialchars($user_email); ?></span>
                            <a class="btn btn-outline-primary mx-2 my-1 btn-sign-in" href="logout.php">Log Out</a>
                        <?php else: ?>
                            <a class="btn btn-outline-primary mx-2 my-1 btn-sign-in" href="login.php">Log In</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5" style="background-color: #e0f7fa; padding: 20px; border-radius: 10px;">
        <form method="post" enctype="multipart/form-data" id="assignmentForm">
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
                <label for="instructions">Detailed Instructions</label>
                <textarea class="form-control" id="instructions" name="instructions" required></textarea>
            </div>

            <div class="row">
                <div class="form-group col-md-2">
                    <label for="pages">Pages</label>
                    <input type="number" class="form-control" value='1' id="pages" name="pages" required min="1" max="150">
                </div>
                <div class="form-group col-md-2">
                    <label for="resources">Number of Resources</label>
                    <input type="number" class="form-control" value="0" id="resources" name="resources" required min="0">
                </div>

                <div class="form-group col-md-2">
                    <label for="format">Formatting Style</label>
                    <select class="form-control" id="format" name="format" required>
                        <option value="" disabled selected>Select a format</option>
                        <option value="APA">APA</option>
                        <option value="MLA">MLA</option>
                        <option value="Harvard">Harvard</option>
                        <option value="Chicago">Chicago</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="file">Assignment File</label>
                    <input type="file" class="form-control pb-2 pb-5" id="file" name="file" accept=".pdf,.doc,.docx">
                </div>
            </div>


            <div class="row">
                <div class="form-group col-md-4">
                    <label for="priority_level">Priority Level</label>
                    <select class="form-control" id="priority_level" name="priority_level" required style="margin-bottom: 10px;">
                        <option value="" disabled selected>Select priority level</option>
                        <option value="Very Urgent" data-rate="15">Very Urgent - 1 to 12 Hrs</option>
                        <option value="Urgent" data-rate="12">Urgent - 12 to 24 Hrs</option>
                        <option value="Normal" data-rate="10">Normal - 24hrs & Beyond</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="deadline">Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="time">Time</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
            </div>

            <div class="col mt-4 d-flex align-items-center justify-content-between">
                <div>
                    <label for="amount" style="font-weight: bold;">Amount to be Paid:</label>
                    <h3 id="amountDisplay" style="font-size: 1.0rem; color: green; font-weight: bold;">$0.00</h3>
                </div>
                <div id="paypal-button-container" class="ml-4">
                    <button class="btn btn-primary d-flex align-items-center">

                        <i class="fab fa-paypal" style="font-size: 1.5rem; margin-right: 8px;"></i> Pay with PayPal

                    </button>
                </div>
            </div>


            <br>
            <span id="downPaymentText" style="color: green;">
                Kindly enter your email below so we can share <strong id="downPaymentAmount">The Work Results File</strong> once we are done, Thank You!
            </span>

            <br>
            <?php if (!$user_id): ?>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            <?php endif; ?>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <footer class="footer bg-light">
        <div class="container">
            <div class="row mt-4">
                <div class="col-lg-12 text-start">
                    <div class="text-center">
                        <h1 class="fw-bold display-5">Our Services</h1>
                    </div>
                    <div class="row">
                        <!-- Service Columns -->
                        <div class="col-lg-3 col-md-6">
                            <ul class="list-unstyled text-start">
                                <li><a href="#">Admission Essay Writing Service</a></li>
                                <li><a href="#">Analytical Essay Writing Service</a></li>
                                <li><a href="#">APA Paper Writing Service</a></li>
                                <li><a href="#">Argumentative Essay Writing</a></li>
                                <li><a href="#">Book Report Writing Service</a></li>
                                <li><a href="#">Buy Argumentative Essay</a></li>
                                <li><a href="#">Buy Assignment</a></li>
                                <li><a href="#">Buy Biology Paper</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Buy Capstone Project</a></li>
                                <li><a href="#">Buy Case Study</a></li>
                                <li><a href="#">Buy Coursework</a></li>
                                <li><a href="#">Buy Custom Essay</a></li>
                                <li><a href="#">Buy Essay</a></li>
                                <li><a href="#">Buy Literature Essay</a></li>
                                <li><a href="#">Buy Literature Review</a></li>
                                <li><a href="#">Buy Narrative Essay</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Buy Personal Statement</a></li>
                                <li><a href="#">Buy Persuasive Essay</a></li>
                                <li><a href="#">Buy Research Proposal</a></li>
                                <li><a href="#">Buy Speech</a></li>
                                <li><a href="#">Capstone Project Writing</a></li>
                                <li><a href="#">Case Study Writing Service</a></li>
                                <li><a href="#">Coursework Help</a></li>
                                <li><a href="#">Coursework Writing Service</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Descriptive Essay Writing Service</a></li>
                                <li><a href="#">Dissertation Proposal Writing</a></li>
                                <li><a href="#">Do My Assignment</a></li>
                                <li><a href="#">Do My Coursework</a></li>
                                <li><a href="#">Edit My Essay</a></li>
                                <li><a href="#">Essay for Sale</a></li>
                                <li><a href="#">Expository Essay Writing Service</a></li>
                                <li><a href="#">Literature Review Writing Service</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Centered About, Contact, Terms Section -->
            <div class="row mt-4">
                <div class="col-lg-12 text-center my-auto">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item">
                            <a href="contact.html#about">About</a>
                        </li>
                        <li class="list-inline-item">&sdot;</li>
                        <li class="list-inline-item">
                            <a href="contact.html#contact">Contact</a>
                        </li>
                        <li class="list-inline-item">&sdot;</li>
                        <li class="list-inline-item">
                            <a href="contact.html#terms">Terms of Use</a>
                        </li>
                        <li class="list-inline-item">&sdot;</li>
                        <li class="list-inline-item">
                            <a href="contact.html#privacy">Privacy Policy</a>
                        </li>
                    </ul>

                    <div class="container mb-3 ">
                        <div class="row d-flex justify-content-center align-items-center text-center">
                            <div class="col-12 col-md-auto mb-2">
                                <a href="mailto:support@expertsdom.com">support@expertsdom.com</a>
                            </div>

                        </div>
                    </div>

                    <p class="text-center">
                        <strong>&copy; 2025 Expertsdom. All rights reserved. Designed by Expertsdom Team.</strong>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pagesInput = document.getElementById("pages");
            const prioritySelect = document.getElementById("priority_level");
            const amountDisplay = document.getElementById("amountDisplay");
            const amountInput = document.getElementById("amount");
            const downPaymentAmount = document.getElementById("downPaymentAmount");

            // Function to calculate and update amounts
            function calculateAmount() {
                // Ensure inputs exist before proceeding
                if (!pagesInput || !prioritySelect || !amountDisplay || !downPaymentAmount) return;

                const pages = parseInt(pagesInput.value) || 0; // Get pages input
                const selectedOption = prioritySelect.options[prioritySelect.selectedIndex]; // Get selected option
                const rate = selectedOption ? parseFloat(selectedOption.dataset.rate) || 0 : 0; // Get rate

                const total = pages * rate; // Calculate total amount
                const downPayment = total * 0.2; // Calculate 20% down payment

                // Update the displayed values dynamically
                amountDisplay.textContent = `$${total.toFixed(2)}`;
                amountInput.value = total.toFixed(2);
                downPaymentAmount.textContent = `$${downPayment.toFixed(2)}`;
            }

            // Ensure elements exist before adding event listeners
            if (pagesInput) pagesInput.addEventListener("input", calculateAmount);
            if (prioritySelect) prioritySelect.addEventListener("change", calculateAmount);

            // Initial calculation on page load
            calculateAmount();
        });
    </script>

    <script src="vendor/jquery/jquery.min.js">
    </script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>