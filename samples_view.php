<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
} else {
    // Handle the file upload process
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $topic = $_POST['topic'];

        // File upload logic
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $file_name = $_FILES['pdf_file']['name'];
            $file_tmp = $_FILES['pdf_file']['tmp_name'];
            $file_size = $_FILES['pdf_file']['size'];
            $file_type = $_FILES['pdf_file']['type'];

            // Set file upload directory
            $upload_dir = 'uploads/samples/';
            $file_path = $upload_dir . basename($file_name);

            // Check if the file is a PDF
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($file_extension != 'pdf') {
                echo '<script>alert("Only PDF files are allowed.")</script>';
            } else {
                // Move uploaded file to the server directory
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Insert into database
                    $sql = "INSERT INTO samples (title, topic, file_path) 
                            VALUES (:title, :topic, :file_path)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':title', $title, PDO::PARAM_STR);
                    $query->bindParam(':topic', $topic, PDO::PARAM_STR);
                    $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);

                    if ($query->execute()) {
                        echo '<script>alert("Sample uploaded successfully!")</script>';
                    } else {
                        echo '<script>alert("Error uploading sample. Please try again.")</script>';
                    }
                } else {
                    echo '<script>alert("Error moving the uploaded file. Please try again.")</script>';
                }
            }
        }
    }

    // Fetch samples from the database based on topic
    $query = "SELECT * FROM samples ORDER BY topic";
    $stmt = $dbh->prepare($query);
    $stmt->execute();

    // Fetch results
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title>Expertsdom</title>
        <link rel="icon" href="img/globe.png" type="image/x-icon">

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Custom fonts for this template -->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
        <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
            type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


        <!-- Custom styles for this template -->
        <link href="css/landing-page.min.css" rel="stylesheet" />

        <style>
            #brandText {
                font-weight: bold;
                /* Makes text bold */
                font-size: 1.5rem;
                /* Adjusts the font size, 1.5rem is larger than the default */
            }

            ul.text-start {
                padding-left: 0;
                /* Remove default padding */
                list-style-position: inside;
                /* Align bullets with text */
            }

            p {
                margin-bottom: 20px;
            }

            .btn-outline-info {
                padding: 10px 20px;
                font-size: 16px;
                text-transform: uppercase;
                font-weight: bold;
            }

            .modal-header {
                position: relative;
                padding: 0;
                /* Remove padding to make the image fill the space */
            }

            .modal-image {
                width: 100%;
                height: 50%;
                /* Make the image take the top half */
                object-fit: cover;
                /* Ensures the image covers the area properly */
            }

            .modal-body {
                padding-top: 20px;
                /* Adds spacing between the image and content */
            }

            /* Close button on top of the image */
            .close {
                position: absolute;
                top: 10px;
                /* Adjust as needed */
                right: 10px;
                /* Adjust as needed */
                z-index: 10;
                /* Make sure it stays above the image */
            }

            /* Close button on top of the image */
            .close {
                position: absolute;
                top: 10px;
                /* Adjust as needed */
                right: 10px;
                /* Adjust as needed */
                z-index: 10;
                /* Make sure it stays above the image */
                font-size: 4rem;
                /* Make the icon bigger */
                color: rgb(0, 0, 0);
                /* Make the icon red */
            }

            .close:hover {
                color: darkred;
                /* Dark red on hover */
                text-decoration: none;
                /* Remove underline on hover */
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>

    <body>
        <!-- Navigation -->
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
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Essay Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Research Paper Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Thesis Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Assignment Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Speech Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Book Report Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Lab Report Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Capstone Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Case Study Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Literature Review Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Personal Statement Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Coursework Writing</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#essayWritingModal">Homework Writing</a>
                            </div>
                        </div>
                        <a class="nav-item nav-link" href="samples_view.php">Samples</a>
                        <a class="nav-item nav-link" href="blog.html">Blog</a>
                        <a class="btn btn-primary mx-2 my-1 btn-sign-in" href="orderdetails.php">Order Now</a>
                        <a href="login.php" class="btn btn-outline-primary mx-2 my-1 btn-sign-in">Login</a>
                        <a href="signup.php" class="btn btn-outline-primary mx-2 my-1 btn-sign-in">Signup</a>
                    </div>
                </div>
            </div>
        </nav>

        <body>



            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <div class="text-center">
                    <h1 class="fw-bold display-5">Samples</h1>
                </div>
                <br>
                <div class="col-lg-12">
                    <!-- Display Existing Samples by Topic -->
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <?php
                                    $cnt = 1;
                                    if ($stmt->rowCount() > 0) {
                                        foreach ($samples as $row) {
                                            echo "<div class='col-md-3 mb-4'>"; // Each item in 1 of 4 columns

                                            echo "<div class='card' style='box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";

                                            // Add Image at the top
                                            echo "<img src='./staff_images/abs2.jpg' class='card-img-top' alt='" . htmlentities($row['title']) . "' style='object-fit: cover; height: 200px;'>";

                                            // Card body with topic in red and title below the image
                                            echo "<div class='card-body text-center'>";
                                            echo "<p class='card-text text-danger font-weight-bold'>" . htmlentities($row['topic']) . "</p>";
                                            echo "<h5 class='card-title font-weight-bold' style='font-size: 1.2rem;'>" . htmlentities($row['title']) . "</h5>";
                                            echo "<a href='" . htmlentities($row['file_path']) . "' target='_blank' class='btn btn-info mt-2'>View PDF</a>";
                                            echo "</div>"; // Closing card-body
                                            echo "</div>"; // Closing card

                                            echo "</div>"; // Closing col-md-3 (each column)
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<p class='alert alert-warning text-center'>No samples found</p>";
                                    }
                                    ?>
                                </div> <!-- Closing row -->
                            </div> <!-- Closing container -->
                        </div> <!-- Closing card-body -->
                    </div> <!-- Closing card -->
                </div> <!-- Closing col-lg-12 -->
            </div> <!-- Closing content-wrapper -->



            <!-- Footer -->
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

                    <!-- New Section: Compliance and Legal Use Information -->
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="text-center">
                                <p>
                                    <strong>Use of Expertsdom.com complies with educational standards and
                                        does not violate any regulations issued by educational
                                        institutions.</strong>
                                    <br /><strong>Our services are completely LEGAL and can be used
                                        for:</strong>
                                </p>

                                <ol class="text-left d-inline-block">
                                    <li>
                                        Additional insight to the subject with appropriate detailing
                                        of key questions to cover.
                                    </li>
                                    <li>
                                        Provision of reasoning for your own statements and further
                                        research.
                                    </li>
                                    <li>
                                        Paraphrasing according to education guidelines issued by your
                                        college or university in regard to plagiarism and acceptable
                                        paraphrase.
                                    </li>
                                    <li>
                                        Use of citations (please follow the citation recommendations).
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Centered About, Contact, Terms Section -->
                    <div class="row mt-4">
                        <div class="col-lg-12 text-center my-auto">
                            <!-- Links Section -->
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

                            <!-- Contact Information Section -->
                            <div class="container mb-3 ">
                                <div class="row d-flex justify-content-center align-items-center text-center">
                                    <div class="col-12 col-md-auto mb-2">
                                        <i class="fas fa-envelope text-primary"></i>
                                        <a href="mailto:support@expertsdom.com">support@expertsdom.com</a>
                                    </div>
                                    <div class="col-12 col-md-auto mb-2">
                                        <!-- <i class="fas fa-phone text-primary"></i> -->
                                        <!-- <a href="tel:+254707482001">0707482001</a> -->
                                    </div>
                                </div>
                            </div>

                            <!-- Copyright Section -->
                            <p class="text-muted small mb-4 mb-lg-0">
                                &copy; Expertsdom 2024. All Rights Reserved.
                            </p>
                        </div>

                        <div class="col-lg-12 text-center my-auto">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item mr-3">
                                    <a href="#"><i class="fab fa-facebook fa-2x fa-fw"></i></a>
                                </li>
                                <li class="list-inline-item mr-3">
                                    <a href="#"><i class="fab fa-twitter-square fa-2x fa-fw"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#"><i class="fab fa-instagram fa-2x fa-fw"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>

            <script>
                document
                    .getElementById("toggle-button")
                    .addEventListener("click", function() {
                        document.getElementById("additional-info").style.display = "block";
                        this.style.display = "none"; // Hide the "Read more" button
                    });

                document
                    .getElementById("toggle-button-less")
                    .addEventListener("click", function() {
                        document.getElementById("additional-info").style.display = "none";
                        document.getElementById("toggle-button").style.display =
                            "inline-block"; // Show the "Read more" button
                    });
            </script>
            <!-- Bootstrap core JavaScript -->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
            <script>
                $(document).ready(function() {
                    // Toggle hidden content on 'Read more' click
                    $("#toggle-button").on("click", function(e) {
                        e.preventDefault();
                        $("#additional-info").slideDown("slow"); // Show with smooth slide animation
                        $(this).hide(); // Hide 'Read more' button
                    });

                    // Hide content on 'Read less' click
                    $("#toggle-button-less").on("click", function() {
                        $("#additional-info").slideUp("slow", function() {
                            // Scroll back to the original section
                            document.querySelector(".showcase").scrollIntoView({
                                behavior: "smooth"
                            });
                        });
                        $("#toggle-button").show(); // Show 'Read more' button
                    });
                });
            </script>

        </body>

    </html>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>

<?php } ?>