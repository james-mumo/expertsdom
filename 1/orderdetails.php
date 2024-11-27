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

    .hero-section {
      background-color: #f8f9fa;
      padding: 50px 0;
      text-align: center;
    }

    .hero-section h1 {
      font-size: 2.5rem;
      font-weight: 700;
    }

    .cta-buttons {
      margin-top: 30px;
    }

    .cta-buttons .btn {
      padding: 15px 30px;
      font-size: 1.1rem;
    }

    .pricing-table {
      display: flex;
      justify-content: space-around;
      margin-top: 50px;
    }

    .pricing-card {
      background: #ffffff;
      border: 1px solid #ddd;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 250px;
      text-align: center;
    }

    .pricing-card h3 {
      font-size: 1.5rem;
      margin-bottom: 20px;
    }

    .pricing-card p {
      font-size: 1.1rem;
      margin-bottom: 20px;
    }

    .pricing-card .price {
      font-size: 2rem;
      font-weight: bold;
      color: #007bff;
    }

    .available-subjects {
      margin-top: 50px;
      text-align: center;
    }

    .available-subjects h2 {
      font-size: 2rem;
      font-weight: 700;
    }

    .subject-list {
      list-style-type: none;
      padding-left: 0;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }

    .subject-list li {
      margin: 5px 20px;
      font-size: 1.1rem;
    }

    .hover-effect:hover {
      cursor: pointer;
      border-color: #018a94;
      border: 1px solid #018a94;
      /* Teal color */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 9px;
    }

    .border_l {
      border-radius: 4px;
      cursor: pointer;
      border-color: #018a94;
      border: 1px solid #018a94;
    }
  </style>
</head>

<body class="bg-light">
  <nav class="navbar navbar-light bg-light static-top mb-3">
    <div class="container">
      <a class="navbar-brand" href="index.html" id="brandText">Expertsdom</a>
      <div class="ml-auto d-flex align-items-center">
        <a class="nav-item nav-link" href="login.php">Log In</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <h1>Sign Up or Login to Order Now!</h1>
    <p>Get affordable assignments with guaranteed quality. Start your academic journey with us!</p>
    <div class="cta-buttons">
      <a href="login.php" class="btn btn-primary">Log In</a>
      <a href="signup.php" class="btn btn-outline-primary">Sign Up</a>
    </div>
  </section>


  <!-- Available Subjects Section (Moved to the last) -->
  <div class="available-subjects bg-light">
    <h2 class="text-center mb-4">Available Subjects</h2>
    <div class="d-flex justify-content-center">
      <div class="row text-center">
        <!-- First column -->
        <div class="col-md-3">
          <ul class="list-unstyled">
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Mathematics</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Biology</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Political Science</li>
          </ul>
        </div>

        <!-- Second column -->
        <div class="col-md-3">
          <ul class="list-unstyled">
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Chemistry</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Engineering</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Computer Science</li>
          </ul>
        </div>

        <!-- Third column -->
        <div class="col-md-3">
          <ul class="list-unstyled">
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">History</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Psychology</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Business Studies</li>
          </ul>
        </div>

        <!-- Fourth column -->
        <div class="col-md-3">
          <ul class="list-unstyled">
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Sociology</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Physics</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Literature</li>
            <li class="border_l rounded-3 p-2 mb-2 text-center hover-effect">Economics</li>
          </ul>
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

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>