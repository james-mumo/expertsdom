<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log"); // Logs errors to a file

include('includes/dbconnection.php');

try {
    // Fetch blogs from the database
    $query = "SELECT * FROM blogs ORDER BY created_at DESC";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Expertsdom | Blog</title>
    <link rel="icon" href="img/globe.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="css/samples-page.css" rel="stylesheet" />
    <!-- PDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        .blog-card {
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: 0.3s;
        }

        .blog-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }

        .blog-image {
            background-size: cover;
            height: 180px;
        }

        .blog-content {
            padding: 10px;
            flex-grow: 1;
        }

        .blog-badge {
            display: inline-block;
            background-color: #0049b8;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .modal-body {
            text-align: left;
            padding: 20px;
        }

        .modal-body h2 {
            font-size: 22px;
            color: #0056b3;
            margin-top: 15px;
        }

        .modal-body p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        .modal-body em {
            font-size: 16px;
            font-style: italic;
            color: #555;
        }
    </style>
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
            <h1>Expertsdom Blog</h1>
            <p>Аn ultimate resource for students that provides paper writing Guides, Topic ideas and Samples, writing tips
            </p>
        </div>
    </div>
    <div class="container my-5">
        <h2 class="text-center my-4">Latest Blog Posts</h2>

        <div class="row">
            <?php foreach ($blogs as $blog) { ?>
                <div class="col-lg-3 col-md-4 mt-4 col-12" style="padding: 0 15px; cursor: pointer;">
                    <div class="blog-card" data-bs-toggle="modal" data-bs-target="#blogModal"
                        data-title="<?php echo htmlspecialchars($blog['title']); ?>"
                        data-image="<?php echo !empty($blog['image_url']) ? htmlspecialchars($blog['image_url']) : './staff_images/abs1.jpg'; ?>"
                        data-blogtype="<?php echo htmlspecialchars($blog['blog_type']); ?>"
                        data-date="<?php echo date('F d, Y', strtotime($blog['created_at'])); ?>"
                        data-subheading1="<?php echo htmlspecialchars($blog['subheading1']); ?>"
                        data-subcontent1="<?php echo nl2br(htmlspecialchars($blog['subcontent1'])); ?>"
                        data-subheading2="<?php echo htmlspecialchars($blog['subheading2']); ?>"
                        data-subcontent2="<?php echo nl2br(htmlspecialchars($blog['subcontent2'])); ?>"
                        data-subheading3="<?php echo htmlspecialchars($blog['subheading3']); ?>"
                        data-subcontent3="<?php echo nl2br(htmlspecialchars($blog['subcontent3'])); ?>"
                        data-conclusion="<?php echo nl2br(htmlspecialchars($blog['conclusion'])); ?>">

                        <div class="blog-image" style="background-image: url('<?php echo !empty($blog['image_url']) ? htmlspecialchars($blog['image_url']) : './staff_images/abs1.jpg'; ?>');">
                        </div>

                        <div class="blog-content">
                            <span class="blog-badge"><?php echo htmlspecialchars($blog['blog_type']); ?></span>
                            <h5 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 10px;">
                                <?php echo htmlspecialchars($blog['title']); ?>
                            </h5>
                            <p style="font-size: 0.8rem; color: #555; margin-bottom: 10px;">
                                <span style="font-weight: bold;"><?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                            </p>
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="blogModal" tabindex="-1" aria-labelledby="blogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="modalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent" style="padding: 20px;"></div>
                <div class="modal-footer d-flex justify-content-center" style="padding: 15px;">
                    <button id="downloadPDF" class="btn btn-primary me-3">Download PDF</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



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
                        <strong>&copy; 2024 Expertsdom. All rights reserved. Designed by Expertsdom Team.</strong>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Tawk.to Live Chat -->
    <script type="text/javascript">
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/61a4f66f88f2e716e8fc86bb/1d8if85j0';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var blogCards = document.querySelectorAll(".blog-card");
            var modalTitle = document.getElementById("modalTitle");
            var modalContent = document.getElementById("modalContent");
            var downloadBtn = document.getElementById("downloadPDF");

            blogCards.forEach(function(card) {
                card.addEventListener("click", function() {
                    modalTitle.innerText = card.getAttribute("data-title");

                    // Build modal content
                    var contentHTML = `<p><strong>Date:</strong> ${card.getAttribute("data-date")}</p><hr>`;

                    ["subheading1", "subheading2", "subheading3"].forEach((key, index) => {
                        if (card.getAttribute("data-" + key)) {
                            contentHTML += `
                        <h3>${card.getAttribute("data-" + key)}</h3>
                        <p>${card.getAttribute("data-subcontent" + (index + 1))}</p>
                        <hr>`;
                        }
                    });

                    contentHTML += `<h3>Conclusion</h3><p><em>${card.getAttribute("data-conclusion")}</em></p>`;

                    modalContent.innerHTML = contentHTML;

                    // PDF Download Functionality
                    downloadBtn.onclick = function() {
                        const {
                            jsPDF
                        } = window.jspdf;
                        var doc = new jsPDF();
                        let yOffset = 10;

                        // Title
                        doc.setFontSize(16);
                        doc.text(modalTitle.innerText, 10, yOffset);
                        yOffset += 10;

                        // Extract structured content
                        modalContent.querySelectorAll("p, h3, em, hr").forEach((el) => {
                            if (el.tagName === "H3") {
                                doc.setFontSize(14);
                                doc.text(el.innerText, 10, yOffset);
                                yOffset += 8;
                            } else if (el.tagName === "P" || el.tagName === "EM") {
                                doc.setFontSize(12);
                                doc.text(el.innerText, 10, yOffset);
                                yOffset += 7;
                            } else if (el.tagName === "HR") {
                                yOffset += 5; // Space for separator
                            }
                        });

                        doc.save(modalTitle.innerText + ".pdf");
                    };
                });
            });
        });
    </script>


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>