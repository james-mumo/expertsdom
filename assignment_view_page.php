<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('includes/dbconnection.php');

// Ensure the user is logged in
if (!isset($_SESSION['sid'])) {
    header('location:logout.php');
    exit();
}

$assignment = null; // Initialize assignment variable

if (isset($_GET['id'])) {
    $view_id = $_GET['id'];

    // Use a prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM assignments WHERE id = ?");
    $stmt->bind_param("i", $view_id); // 'i' means integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $assignment = $result->fetch_assoc(); // Store result in $assignment

        // Calculate remaining time for the deadline
        $deadline = new DateTime($assignment['deadline']);
        // $order_amount = $row(['order_amount']);
        $current_time = new DateTime();
        $interval = $current_time->diff($deadline);
        $remaining_time = $interval->format('%d days, %h hours');
    } else {
        $error_message = "Assignment not found."; // Set error message
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php @include("includes/header.php"); ?>
        <!-- Main Sidebar Container -->
        <?php @include("includes/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Assignment Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Assignment Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="">
                        <div class="card-header">
                            <h3 class="card-title">Assignment Information</h3>
                        </div>

                        <div class="card-body" style="background-color:rgba(7, 134, 134, 0.29); padding: 8px 20px;">
                            <?php if ($assignment) { ?>
                                <!-- Subject and Topic -->
                                <div style=" display: flex; justify-content: space-between; margin-bottom: 4px; padding: 8px 20px;">
                                    <div style="flex: 1; padding-right: 10px;">
                                        <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Subject:</p>
                                        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                                            <?php echo htmlentities($assignment['subject']); ?>
                                        </p>

                                    </div>
                                    <div style="flex: 1; padding-left: 10px;">
                                        <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Topic:</p>
                                        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($assignment['topic']); ?></p>
                                    </div>
                                </div>
                                <!-- <div style="display: flex; flex:1; justify-content: space-between; margin-bottom: 10px; padding:8px 20px;"> -->

                                <!-- <div style="display: flex; justify-content: space-between; font-size: 16px; color: #555;"> -->
                                <!-- <div style="flex: 1; padding-right: 10px;">
                                        <strong style="color: #00796b;">Deadline:</strong>
                                        <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">

                                            <?php
                                            // Assuming $row['deadline'] is in a standard date format (e.g., 'YYYY-MM-DD')
                                            $deadline = $assignment['deadline'];
                                            $formatted_date = date('d F Y', strtotime($deadline)); // d for day, F for full month name, Y for year
                                            echo htmlentities($formatted_date);
                                            ?>
                                        </span>

                                    </div> -->

                                <!-- <div style="flex: 1; padding-right: 10px;">
                                        <p><strong style="color: #00796b;"> Time Remaining:</strong><span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;"> <?php echo $remaining_time; ?></span></p>
                                    </div>
                                    <div style="flex: 1; padding-right: 10px;">
                                        <p style="font-size: 1rem; color: #00796b; margin: 5px 0;">
                                            <span style="font-weight: bold;">Amount to Pay:</span>
                                            <span style="color: #388e3c; background-color:#f7f7f7; padding: 5px; border-radius: 4px; ">$<?php echo $assignment['order_amount']; ?></span>
                                        </p>
                                    </div> -->

                                <!-- </div> -->

                                <!-- <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                    <div style="display:flex; padding-right: 10px;">

                                        <p><strong style="color: #00796b;">Submitted On:</strong>
                                            <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                                                <?php
                                                // Assuming $assignment['submitted_on'] is a valid datetime
                                                $submitted_on = $assignment['submitted_on'];

                                                // Format the datetime (Day, Month Date, Year - Time AM/PM)
                                                echo date('F j, Y - h:i A', strtotime($submitted_on));
                                                ?>

                                            </span>
                                        </p>
                                    </div>
                                    <div style="flex: 1; padding-right: 10px;">
                                        <p style="background-color: #f7f7f7; padding: 10px; border-radius: 4px;">
                                            <strong style="color: #00796b;">File(s) Attached:</strong>
                                            <br>
                                            <?php
                                            // Check if there are files attached
                                            if ($assignment['file']) {
                                                // Split the filenames by '@'
                                                $files = explode('@@@', $assignment['file']);

                                                // Loop through the files and display them with a number
                                                foreach ($files as $index => $file) {
                                                    // Clean the file name
                                                    $file = trim($file); // Remove any unwanted spaces
                                                    $file_path = 'assignmentuploads/' . htmlentities($file);
                                                    echo ($index + 1) . ".<a href='" . $file_path . "' target='_blank'>" . htmlentities($file) . "</a><br>";
                                                }
                                            } else {
                                                echo 'None';
                                            }
                                            ?>

                                        </p>
                                    </div>
                                </div> -->


                                <!-- Instructions -->
                                <div style="margin-bottom: 0px; padding:8px 20px;">
                                    <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 0px;">Instructions:</p>
                                    <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px 5px 15px 5px; border-radius: 4px;">
                                        <?php echo htmlentities($assignment['instructions']); ?>
                                    </p>

                                </div>

                                <!-- 3 Columns: Resources, Format, Pages -->
                                <div style="display: flex; flex:1; justify-content: space-between; margin-bottom: 0px; padding:8px 20px;">
                                    <div style="flex: 1; padding-right: 10px;">
                                        <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Resources:</p>
                                        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($assignment['resources']); ?></p>
                                    </div>
                                    <div style="flex: 1; padding-left: 10px;">
                                        <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Format:</p>
                                        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($assignment['format']); ?></p>
                                    </div>
                                    <div style="flex: 1; padding-left: 10px;">
                                        <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Pages:</p>
                                        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($assignment['pages']); ?></p>





                                        </d
                                            <?php } else { ?>
                                            <p class="text-danger"><?php echo isset($error_message) ? $error_message : "Assignment not found."; ?></p>
                                    <?php } ?>
                                    </div>
                                </div>

                        </div>
                        <div class="" style="padding:8px 20px ">

                            <div style="display: flex; flex:1; justify-content: space-between; margin-bottom: 0px;">

                                <!-- <div style="display: flex; justify-content: space-between; font-size: 16px; color: #555;"> -->
                                <div style="flex: 1; padding-right: 10px;">
                                    <strong style="color: #00796b;">Deadline:</strong>
                                    <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px; flex:1;">

                                        <?php
                                        // Assuming $row['deadline'] is in a standard date format (e.g., 'YYYY-MM-DD')
                                        $deadline = $assignment['deadline'];
                                        $formatted_date = date('d F Y', strtotime($deadline)); // d for day, F for full month name, Y for year
                                        echo htmlentities($formatted_date);
                                        ?>
                                    </span>

                                </div>

                                <div style="flex: 1; padding-right: 10px;">
                                    <p><strong style="color: #00796b;"> Time Remaining:</strong><span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;"> <?php echo $remaining_time; ?></span></p>
                                </div>
                                <div style="flex: 1; padding-right: 10px;">
                                    <p style="font-size: 1rem; color: #00796b; margin: 0px 0;">
                                        <span style="font-weight: bold;">Amount to Pay:</span>
                                        <span style="color: #388e3c; background-color:#f7f7f7; padding: 5px; border-radius: 4px; flex:1;">$<?php echo $assignment['order_amount']; ?></span>
                                    </p>
                                </div>
                                <!--  -->

                                <div style="display:flex; padding-right: 10px;">

                                    <p><strong style="color: #00796b;">Submitted On:</strong>
                                        <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                                            <?php
                                            // Assuming $assignment['submitted_on'] is a valid datetime
                                            $submitted_on = $assignment['submitted_on'];

                                            // Format the datetime (Day, Month Date, Year - Time AM/PM)
                                            echo date('F j, Y - h:i A', strtotime($submitted_on));
                                            ?>

                                        </span>
                                    </p>
                                </div>

                                <!--  -->

                            </div>
                        </div>
                        <div class="test" style="padding: 8px 20px;">

                            <div style="flex: 1; padding-right: 10px;">
                                <p style="background-color: #f7f7f7; padding: 10px; border-radius: 4px;">
                                    <strong style="color: #00796b;">File(s) Attached:</strong>
                                    <br>
                                    <?php
                                    // Check if there are files attached
                                    if ($assignment['file']) {
                                        // Split the filenames by '@'
                                        $files = explode('@@@', $assignment['file']);

                                        // Loop through the files and display them with a number
                                        foreach ($files as $index => $file) {
                                            // Clean the file name
                                            $file = trim($file); // Remove any unwanted spaces
                                            $file_path = 'assignmentuploads/' . htmlentities($file);
                                            echo ($index + 1) . ".<a href='" . $file_path . "' target='_blank'>" . htmlentities($file) . "</a><br>";
                                        }
                                    } else {
                                        echo 'None';
                                    }
                                    ?>

                                </p>
                            </div>
                        </div>

                        <?php
                        // Assuming the amount is fetched from the database

                        $paypalEmail = "wmuasya007@gmail.com"; // PayPal email
                        ?>

                        <?php
                        // Check if there are work results (files)
                        if ($assignment['work_results']) {
                        ?>
                            <div class="container mt-4" style="background-color: #0070ba; padding-left: 20px; padding: 10px; border-radius: 5px;">
                                <div class="row d-flex align-items-center justify-content-center">

                                    <!-- Payment Information (Left) -->
                                    <div class="col-md-6 col-12 p-3"
                                        style="background: #f9f9f9; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                                        <h4 style="font-size: 1.2rem; color: #00796b; font-weight: bold; margin-bottom: 15px; text-align: center;">
                                            Kindly Pay and Download the Attached Work Results
                                        </h4>

                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <p style="font-size: 0.95rem; margin: 0; display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: bold; color: #555;">PayPal Email:</span>
                                                <span style="color: #388e3c; font-weight: bold;"><?php echo $paypalEmail; ?></span>
                                            </p>

                                            <p style="font-size: 0.95rem; margin: 0; display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: bold; color: #555;">Total Amount:</span>
                                                <span style="color: #d32f2f; font-weight: bold;">$<?php echo number_format($assignment['order_amount'], 2); ?></span>
                                            </p>

                                            <p style="font-size: 0.95rem; margin: 0; display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: bold; color: #555;">Amount Paid:</span>
                                                <span style="color: #388e3c; font-weight: bold;">$<?php echo number_format($assignment['amount_paid'], 2); ?></span>
                                            </p>

                                            <p style="font-size: 0.95rem; margin: 0; display: flex; justify-content: space-between; align-items: center;">
                                                <span style="font-weight: bold; color: #555;">Amount to Pay:</span>
                                                <span style="color: #ff9800; font-weight: bold;">
                                                    $<?php echo number_format($assignment['order_amount'] - $assignment['amount_paid'], 2); ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- PayPal Button (Right) -->
                                    <div class="col-md-6 col-12 d-flex justify-content-center p-3">
                                        <a href="#" class="btn btn-primary d-flex flex-column align-items-center justify-content-center"
                                            style="background-color: #0070ba; color: white; padding: 15px 20px; border-radius: 8px; font-size: 1rem; text-decoration: none; width: 100%; max-width: 350px;">

                                            <!-- Large PayPal Icon -->
                                            <i class="fab fa-paypal" style="font-size: 3.5rem; margin-bottom: 10px; color: #ffffff;"></i>

                                            <!-- Pay Now Text -->
                                            <span style="font-size: 1.2rem; font-weight: bold;">Pay Now with PayPal</span>
                                        </a>
                                    </div>

                                </div>
                            </div>



                    </div>
                <?php
                        } // End of if statement for checking if work_results exist
                ?>


                <?php
                // Fetch the amount paid
                $amount_paid = isset($assignment['amount_paid']) ? $assignment['amount_paid'] : 0;
                $order_amount = isset($assignment['order_amount']) ? $assignment['order_amount'] : 0;
                ?>

                <div class="text"></div>
                <!--  -->
                <h4 style="text-align: center; font-size: 22px; color: teal; font-weight: bold; margin-bottom: 2px;  margin-top: 10px; ">Work Results</h4>
                <div style="display: flex; justify-content: space-between; margin: 27px; padding-top: 7px; border-top: 1px solid #ddd; width: 100%; border:#28a745">

                    <!-- <form method="POST" action="" enctype="multipart/form-data" style="width: 100%;"> -->
                    <form method="POST" action="assignment_update_logic.php" enctype="multipart/form-data" style="width: 100%;">


                        <p style="font-family: Arial, sans-serif; color: #00796b;">
                            <strong style="color: #388e3c; font-size: 1.1rem;">Work Results Submitted:</strong>
                            <br>
                            <?php
                            // Check if there are files attached
                            if ($assignment['work_results']) {
                                // Split the filenames by '@'
                                $files = explode('@@@', $assignment['work_results']);

                                // Loop through the files and display them with a number
                                foreach ($files as $index => $file) {
                                    // Clean the file name
                                    $file = trim($file); // Remove any unwanted spaces
                                    $file_path = 'uploads/' . htmlentities($file);
                            ?>
                        <div style="background-color: #e8f5e9; padding: 10px; border-radius: 5px; margin-bottom: 10px; display: flex; flex-direction: column; align-items: flex-start; width: 100%;">
                            <!-- File Name and Link -->
                            <div style="margin-bottom: 10px; overflow-wrap: break-word; word-wrap: break-word; max-width: 100%;">
                                <a href="" target="_blank" style="color: #388e3c; font-size: 1rem; text-decoration: none; font-weight: bold; pointer-events: none;">
                                    <?php echo ($index + 1) . ". " . htmlentities($file); ?>
                                </a>
                            </div>


                            <?php if ($permission === 'User') { ?>
                                <!-- Download Button -->
                                <!-- Download Button -->
                                <a href="<?php echo $file_path; ?>" download
                                    style="background-color: #388e3c; color: white; padding: 5px 15px; border-radius: 5px; font-size: 0.9rem; text-decoration: none; display: inline-block; 
                            <?php echo ($amount_paid < $order_amount) ? 'pointer-events: none; opacity: 0.5;' : ''; ?>">
                                    Download
                                </a>

                                <?php if ($amount_paid < $order_amount) { ?>
                                    <span style="color: #f44336; font-size: 1.1 rem; margin-top: 5px;">Kindly Pay to Download Submitted Work</span>
                                <?php } ?>
                            <?php } ?>


                            <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>
                                <a href="<?php echo $file_path; ?>" download
                                    style="background-color: #388e3c; color: white; padding: 5px 15px; border-radius: 5px; font-size: 0.9rem; text-decoration: none; display: inline-block;">
                                    View Uploaded File
                                </a>
                            <?php } ?>


                        </div>

                <?php
                                }
                            } else {
                                echo '<div style="color: #f44336; background-color:#e8f5e9; padding:4px; width:100%; border-radius: 5px; font-size: 1.3rem;">None</div>';
                            }
                ?>
                </p>


                <?php if ($permission === 'User') { ?>
                    <!-- <div style="margin-bottom: 20px; flex:1;">
                <label for="comments" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Comments from Tutor:</label>
                <textarea id="comments" style="padding: 10px; font-size: 14px; width: 100%; height: 120px; border-radius: 4px; border: 1px solid #ddd;"><?php echo htmlentities($row['comments']); ?></textarea>
            </div> -->
                <?php } ?>

                <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>

                    <!-- <div style="margin-bottom: 20px;">
                                <label for="work_results" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Attach Work Results:</label>
                                <input type="file" name="work_results" accept=".pdf,.doc,.docx" id="work_results" style="padding: 5px; font-size: 14px; width: 100%; border-radius: 4px; border: 1px solid #ddd;" />
                            </div> -->

                    <div style="margin-bottom: 20px;">
                        <label for="work_results" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">
                            Attach Work Results:
                        </label>
                        <input
                            type="file"
                            name="work_results"
                            accept=".pdf,.doc,.docx"
                            id="work_results"
                            style="padding: 5px; font-size: 14px; width: 100%; border-radius: 4px; border: 1px solid #ddd;" />
                    </div>


                    <div style="flex: 1; padding-left: 20px; display: flex; align-items: center;">

                        <input type="hidden" name="work_is_done" value="0">
                        <input type="checkbox" name="work_is_done" id="work_done" value="1"
                            <?php echo ($assignment['work_is_done'] == 1) ? 'checked' : ''; ?>>


                        <label
                            for="work_done"
                            style="font-size: 20px; font-weight: bold; color: teal; cursor: pointer; margin: 0;">
                            Mark as Done
                        </label>
                    </div>
                    <div style="flex: 1; padding-left: 20px; display: flex; align-items: center;">
                        <div class="form-group">
                            <label for="amount_paid">Amount Paid:</label>
                            <input type="number" id="amount_paid" name="amount_paid" class="form-control"
                                value="<?php echo isset($assignment['amount_paid']) ? $assignment['amount_paid'] : ''; ?>"
                                min="0" step="0.01" placeholder="Enter amount paid">
                        </div>


                    </div>
                </div>

                <?php
                    // Check if comments are not null or empty
                    if (!is_null($assignment['comments'])) {
                ?>
                    <div style="margin-bottom: 20px;">
                        <label for="comments" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Comments to Student:</label>
                        <textarea id="comments" style="padding: 10px; font-size: 14px; width: 100%; height: 120px; border-radius: 4px; border: 1px solid #ddd;"><?php echo htmlentities($assignment['comments']); ?></textarea>
                    </div>
                <?php
                    }
                ?>



                </form>

                <div style="text-align: center; margin-top: 20px;">
                    <?php if ($assignment) { ?>
                        <form action="delete_assignment_1.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $assignment['id']; ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 4px; background-color: rgb(230, 1, 1); color: white;" onclick="return confirm('Are you sure you want to delete this assignment?');">
                                Delete Assignment
                            </button>
                        </form>
                    <?php } ?>

                    <button type="button" class="btn" id="save_changes" data-id="<?php echo $assignment['id']; ?>"
                        style="padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 4px; background-color: #28a745; color: white;">
                        Save Changes
                    </button>

                    <!-- Full-width Back Button -->
                    <button type="button" class="btn" onclick="window.location.href='assignments_list.php';"
                        style="display: block; width: 100%; margin-top: 20px; padding: 15px; font-size: 18px; border: none; cursor: pointer; border-radius: 4px; background-color: #007bff; color: white;">
                        Back to All Assignments List View
                    </button>
                </div>


            <?php } ?>






                </div>

                <!--  -->
        </div>
        </section>
    </div>

    <!-- Footer -->
    <?php @include("includes/footer.php"); ?>
    </div>

    <?php @include("includes/foot.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#save_changes').click(function(event) {
                event.preventDefault(); // Prevent default form submission

                var assignment_id = $(this).data('id'); // Get assignment ID
                var amount_paid = $('#amount_paid').val(); // Get amount paid
                var work_is_done = $('#work_done').prop('checked') ? 1 : 0;

                var formData = new FormData();
                formData.append('update', true);
                formData.append('assignment_id', assignment_id);
                formData.append('amount_paid', amount_paid);
                formData.append('work_is_done', work_is_done);

                // Append file if selected
                var file = $('#work_results')[0].files[0];
                if (file) {
                    formData.append('work_results', file);
                }

                $.ajax({
                    url: 'assignment_update_logic.php', // Ensure this is your backend PHP file
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response); // Show success or error message
                        console.log(response);
                    },
                    error: function() {
                        alert('Error updating assignment.');
                    }
                });
            });
        });
    </script>

</body>

</html>