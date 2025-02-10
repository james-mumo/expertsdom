<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log"); // Logs errors to a file

include('includes/dbconnection.php');

try {
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

    // Fetch samples from the database
    $query = "SELECT * FROM samples ORDER BY topic";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Epertsdom Samples</title>
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
    <link href="css/samples-page.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0px;
        }

        .sample-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #fff;
        }

        .sample-card img {
            width: 90%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
        }

        .sample-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            opacity: 33;
        }

        .sample-topic {
            font-size: 14px;
            color: #666;
        }

        .btn-download {
            margin-top: 10px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }

        .sample-list {
            max-width: 600px;
            margin: 20px auto;
        }

        .sample-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .sample-list th,
        .sample-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .sample-list th {
            background: #f4f4f4;
        }

        .error {
            color: red;
        }

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

        /* todays css */

        .sample-card {
            background-color: rgba(14, 199, 183, 0.94);
            position: relative;
            background-size: cover;
            /* Ensures image fills the entire card */
            background-position: center;
            /* Centers the image */
            background-repeat: no-repeat;
            /* Prevents repetition */
            width: 100%;
            height: 300px;
            /* Adjust height as needed */
            display: flex;
            align-items: flex-end;
            /* Positions text at the bottom */
            border-radius: 10px;
            overflow: hidden;
        }

        .sample-content {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            /* White background */
            padding: 15px;
            text-align: center;
            border-radius: 0 0 10px 10px;
            /* Rounded bottom edges */
        }

        .sample-title {
            font-size: 18px;
            font-weight: bold;
        }

        .sample-topic {
            font-size: 14px;
            color: #555;
            margin-bottom: 4px;
        }

        .btn-download {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
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
                    <a class="nav-item nav-link" href="samples_view.php
                    ">Samples</a>
                    <a class="nav-item nav-link" href="blog.php">Blog</a>
                    <a class="btn btn-primary mx-2 my-1 btn-sign-in" href="orderdetails.php">Order Now</a>
                    <a href="login.php" class="btn btn-outline-primary mx-2 my-1 btn-sign-in">Login</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Contact Page Hero Section -->
    <div class="hero d-flex align-items-center">
        <div class="hero-text col-md-6 text-white text-center">
            <h1>Expertsdom Samples</h1>
            <p>Аn ultimate resource for students that provides paper writing Guides, Topic ideas and Samples, writing tips
            </p>
        </div>
    </div>


    <div class="container">
        <h2 class="text-center my-4">Uploaded Samples</h2>

        <div class="row g-4">
            <?php foreach ($samples as $sample) { ?>
                <div class="col-md-3 col-sm-6 mt-3">
                    <div class="sample-card shadow-sm mt-2" style="background-image: url('./staff_images/pdf.webp');">
                        <div class="sample-content">
                            <div class="sample-title"><?php echo htmlspecialchars($sample['title']); ?></div>
                            <div class="sample-topic"><?php echo htmlspecialchars($sample['topic']); ?></div>
                            <a href="<?php echo htmlspecialchars($sample['file_path']); ?>" target="_blank" class="btn btn-primary btn-sm btn-download">
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>

</body>

</html>