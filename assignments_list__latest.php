<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set("display_errors", 1); // Display errors on the screen
include "includes/dbconnection.php";

if (strlen($_SESSION["sid"]) == 0) {
    header("location:logout.php");
}

// Handle form submission from the modal
if (isset($_POST["submit_assignment"])) {
    // Collect form data
    $deadline = $_POST["deadline"];
    $time = $_POST["time"];
    $pages = $_POST["pages"];
    $subject = $_POST["subject"];
    $topic = $_POST["topic"];
    $resources = $_POST["resources"];
    $instructions = $_POST["instructions"];
    $priority_level = $_POST["priority_level"];
    $format = $_POST["format"];
    $amount = $_POST["amount"];

    // Retrieve email from session
    $email = $_SESSION["email"] ?? null; // Assuming email is stored in the session

    if (!$email) {
        echo "<script>alert('Unable to retrieve user email. Please log in again.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    }

    // Validate pages limit
    if ($pages > 1500) {
        echo "<script>alert('The maximum number of pages is 1499.');</script>";
    } else {
        // Handle file upload if files are provided
        $file_names_str = ''; // Initialize an empty string for file names

        if (isset($_FILES["file"]) && !empty($_FILES["file"]["name"][0])) {
            $file_names = []; // Array to hold the names of the uploaded files

            // Loop through each uploaded file
            foreach ($_FILES["file"]["name"] as $index => $file_name) {
                $file_tmp = $_FILES["file"]["tmp_name"][$index];

                // Replace spaces with underscores and sanitize the filename
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = preg_replace("/[^A-Za-z0-9_\-\.]/", "", $file_name); // Remove special characters

                // Move the file to the uploads directory
                if (move_uploaded_file($file_tmp, "assignmentuploads/" . $file_name)) {
                    // Add the file name to the array
                    $file_names[] = $file_name;
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Error uploading file: " . $file_name,
                    ]);
                    exit();
                }
            }

            // Combine the file names into a single string, separated by '@@@'
            $file_names_str = implode("@@@", $file_names);
        }

        // Prepare the SQL statement to insert the assignment
        $sql = "INSERT INTO assignments (email, deadline, time, pages, subject, topic, resources, instructions, format, file, priority_level, submitted_on, order_amount) 
                VALUES ('$email', '$deadline', '$time', '$pages', '$subject', '$topic', '$resources', '$instructions', '$format', '$file_names_str', '$priority_level', NOW(), $amount)";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('Assignment submitted successfully.');</script>";
            echo "<script>window.location.href = 'assignments_list.php';</script>";
        } else {
            $error = mysqli_error($con); // Get MySQL error
            echo "<script>alert('Error: " . addslashes($error) . "');</script>";
        }
    }
}


// Check if del and id are set
if (isset($_POST["del"]) && isset($_POST["id"])) {
    // Sanitize the input
    $id = mysqli_real_escape_string($con, $_POST["id"]);

    // Debugging output for server-side logs
    error_log("Attempting to delete assignment with ID: $id");

    // Execute the query
    $query = mysqli_query($con, "DELETE FROM assignments WHERE id = '$id'");

    if ($query) {
        $_SESSION["delmsg"] = "Assignment deleted !!";
        echo json_encode([
            "status" => "success",
            "message" => "Deletion successful.",
        ]);
    } else {
        $error = mysqli_error($con);
        $_SESSION["delmsg"] = "Error deleting assignment: " . $error;

        error_log("SQL Error: $error"); // Log database errors
        echo json_encode([
            "status" => "error",
            "message" => "Deletion failed: " . $error,
        ]);
    }
    // exit();
}

?>

<!DOCTYPE html>
<html>
<?php @include "includes/head.php"; ?>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">
        <!-- Navbar -->
        <?php @include "includes/header.php"; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php @include "includes/sidebar.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Assignment Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Manage Assignments</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Manage Assignments</h3>
                                    <div class="card-tools">
                                        <!-- Button to open modal for adding a new assignment -->
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAssignmentModal"><span style="color: #fff;"><i class="fas fa-plus"></i> New Assignment</span></button>
                                    </div>
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body mt-2">
                                    <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Deadline</th>
                                                <th>Subject</th>
                                                <th>Topic</th>
                                                <th>Pages</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Get the logged-in user's email and permission from the session
                                            $email = $_SESSION["email"] ?? null;
                                            $permission = $_SESSION["permission"] ?? null;

                                            if ($email) {
                                                // Check if the user has 'Admin' or 'Super User' permission
                                                if ($permission === "Admin" || $permission === "Super User") {
                                                    // If the user is an admin or super user, show all assignments
                                                    $query = mysqli_query($con, "SELECT * FROM assignments");
                                                } else {
                                                    // Otherwise, filter by the logged-in user's email
                                                    $query = mysqli_query(
                                                        $con,
                                                        "SELECT * FROM assignments WHERE email = '$email'"
                                                    );
                                                }

                                                // Counter for the row number
                                                $cnt = 1;

                                                // Fetch and display assignments
                                                while ($row = mysqli_fetch_array($query)) { ?>
                                                    <tr class="assignment-row" data-id="<?php echo $row['id']; ?>">
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($row['deadline']); ?></td>
                                                        <td><?php echo htmlentities($row['subject']); ?></td>
                                                        <td><?php echo htmlentities($row['topic']); ?></td>
                                                        <td><?php echo htmlentities($row['pages']); ?></td>
                                                        <td>
                                                            <button class="btn btn-info btn-xs view_data" id="<?php echo $row['id']; ?>">View</button>
                                                            <button class="btn btn-danger btn-xs delete_data" data-id="<?php echo $row['id']; ?>">Delete</button>
                                                        </td>
                                                    </tr>

                                            <?php $cnt++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No assignments available or user not logged in.</td></tr>";
                                            }
                                            ?>
                                        </tbody>


                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->






        <!-- Modal for adding new assignment -->
        <div class="modal fade" id="addAssignmentModal" tabindex="-1" role="dialog" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAssignmentModalLabel" style="font-size: 1.5rem; font-weight: bold; color: #5A5A5A; text-align: center; padding: 10px 0;"> Add New Assignment</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="font-family: Arial, sans-serif; padding: 20px;">
                        <form method="post" enctype="multipart/form-data" action="" id="assignmentForm">
                            <input type="hidden" name="submit_assignment" value="1">
                            <!-- Subject and Topic -->

                            <input type="hidden" name="amount" id="amount">


                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <select class="form-control" id="subject" name="subject" required style="margin-bottom: 10px;">
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
                                <label for="topic">Topic</label>
                                <input type="text" class="form-control" id="topic" name="topic" required style="margin-bottom: 10px;">
                            </div>

                            <!-- Instructions -->
                            <div class="form-group">
                                <label for="instructions">Instructions</label>
                                <textarea class="form-control" id="instructions" name="instructions" required style="margin-bottom: 10px;"></textarea>
                            </div>

                            <!-- Attachment -->
                            <div class="form-group">
                                <label for="file">Assignment File</label>
                                <input type="file" class="form-control" id="file" multiple name="file[]" accept=".pdf,.doc,.docx,.zip,.rar" style="margin-bottom: 10px;">
                            </div>

                            <!-- Sources, Number of Pages, Deadline and Time -->
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-4">
                                    <label for="resources">Resources</label>
                                    <input type="number" class="form-control" id="resources" name="resources" required value="1">

                                </div>
                                <div class="col-md-4">
                                    <label for="pages">Pages</label>
                                    <input type="number" value="1" class="form-control" id="pages" name="pages" required max="150">
                                </div>
                                <div class="col-md-4">
                                    <label for="deadline">Deadline</label>
                                    <input type="date" class="form-control" id="deadline" name="deadline" required min="<?php echo date(
                                                                                                                            "Y-m-d"
                                                                                                                        ); ?>">
                                </div>
                            </div>


                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-4">
                                    <label for="time">Time</label>
                                    <input type="time" class="form-control" id="time" name="time" required ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority_level">Priority Level</label>
                                        <select class="form-control" id="priority_level" name="priority_level" required style="margin-bottom: 10px;">
                                            <option value="" disabled selected>Select priority level</option>
                                            <option value="Very Urgent" data-rate="15">Very Urgent - 1 to 12 Hrs</option>
                                            <option value="Urgent" data-rate="12">Urgent - 12 to 24 Hrs</option>
                                            <option value="Normal" data-rate="10">Normal - 24hrs & Beyond</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- New dropdown for format -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="format">Format</label>
                                        <select class="form-control" id="format" name="format" required style="margin-bottom: 10px;">
                                            <option value="" disabled selected>Select format</option>
                                            <option value="APA">APA</option>
                                            <option value="MLA">MLA</option>
                                            <option value="Chicago">Chicago</option>
                                            <option value="Harvard">Harvard</option>
                                        </select>
                                    </div>
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


                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 48%;">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitAssignmentBtn" style="width: 48%;">Submit</button>
                    </div>

                    </form>
                </div>

            </div>
        </div>
    </div>




    <!-- Modal for Viewing Assignment Details -->
    <form method="POST" id="assignment_form" enctype="multipart/form-data" style="width: 100%;">
        <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
        <!-- Rest of your form fields -->

        <!-- Consistent placement of amount_paid field -->
        <div class="form-group">
            <label for="amount_paid">Amount Paid:</label>
            <input type="number" name="amount_paid" id="amount_paid" class="form-control"
                min="0" step="0.01" placeholder="Enter amount paid"
                value="<?php echo $amount_paid; ?>">
        </div>

        <!-- Rest of your form -->
    </form>
    <!-- /.modal -->

    <?php @include "includes/footer.php"; ?>
    </div>

    <!-- ./wrapper -->
    <?php @include "includes/foot.php"; ?>



    <script type="text/javascript">
        $(document).ready(function() {

            // Add this new handler for the View button
            $(document).on('click', '.view_data', function(e) {
                e.stopPropagation(); // Prevent the row click from also firing
                var view_id = $(this).attr('id'); // Get ID from the button

                // AJAX call to load assignment details
                $.ajax({
                    url: "view_assignment.php",
                    type: "post",
                    data: {
                        view_id: view_id
                    },
                    success: function(data) {
                        $("#info_view").html(data);
                        $("#viewData").modal('show');
                    },
                    error: function() {
                        alert('There was an error retrieving the assignment details.');
                    }
                });
            });

            // $('table#example1 tbody').on('click', 'tr.assignment-row', function() {
            //     var view_id = $(this).data('id'); // Get the ID from the clicked row's data-id

            //     // Trigger the AJAX request like the "View" button click
            //     $.ajax({
            //         url: "view_assignment.php", // URL to fetch assignment details
            //         type: "post",
            //         data: {
            //             view_id: view_id // Pass the ID of the clicked assignment
            //         },
            //         success: function(data) {
            //             $("#info_view").html(data); // Populate the modal with the assignment details
            //             $("#viewData").modal('show'); // Show the modal with the data
            //         },
            //         error: function() {
            //             alert('There was an error retrieving the assignment details.');
            //         }
            //     });
            // });


            // Save changes (mark as done, attach file, and add comments)
            $(document).on('click', '#save_changes', function() {
                var assignment_id = $(this).data('id');
                var is_done = $('#work_done').is(':checked') ? 1 : 0;
                var file_data = $('#work_results').prop('files')[0]; // Get the file
                var comments = $('#comments').val(); // Get the comments from the textarea

                // Get amount_paid from the form within the modal body
                var amount_paid = $('#info_view #amount_paid').val();

                if (amount_paid === '' || isNaN(amount_paid) || amount_paid < 0) {
                    amount_paid = 0; // Default to 0 if invalid
                }

                console.log("Assignment ID:", assignment_id);
                console.log("Amount Paid:", amount_paid);

                var formData = new FormData();
                formData.append('id', assignment_id);
                formData.append('work_is_done', is_done);
                if (file_data) {
                    formData.append('work_results', file_data); // Append the file only if it exists
                }
                formData.append('comments', comments); // Append the comments
                formData.append('amount_paid', amount_paid); // Append the amount paid

                $.ajax({
                    url: "update_assignment.php",
                    type: "post",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Try to parse the response as JSON
                        try {
                            var result = JSON.parse(response);
                            if (result.status === "success") {
                                alert("Changes saved successfully.");
                            } else {
                                alert("Error: " + result.message);
                            }
                        } catch (e) {
                            console.log("Response:", response);
                            alert("Changes saved successfully.");
                        }
                        $("#viewData").modal('hide'); // Close the modal
                        location.reload(); // Reload the page
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        alert("Failed to save changes. Please try again.");
                    }
                });
            });



            // Delete assignment
            $(document).on('click', '.delete_data', function() {
                var delete_id = $(this).data('id'); // Use data attribute
                console.log("Clicked Delete ID:", delete_id);

                if (confirm('Are you sure you want to delete this assignment?')) {
                    $.ajax({
                        url: "delete_assignment.php", // Update URL to your PHP file
                        type: "POST",
                        data: {
                            del: 1,
                            id: delete_id
                        },
                        dataType: "json", // Expect JSON response
                        success: function(response) {
                            console.log("Server Response:", response);
                            if (response.status === "success") {
                                alert(response.message);
                                location.reload(); // Reload the page on success
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("AJAX Error: " + textStatus + ", " + errorThrown);
                            alert("Failed to connect to the server.");
                        }
                    });
                }
            });




        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>


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


    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID"></script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pagesInput = document.getElementById("pages");
            const prioritySelect = document.getElementById("priority_level");
            const amountDisplay = document.getElementById("amountDisplay");
            const amountInput = document.getElementById("amount");


            // Function to calculate and display the amount
            function calculateAmount() {
                const pages = parseInt(pagesInput.value) || 0; // Get the number of pages
                const rate = parseInt(prioritySelect.options[prioritySelect.selectedIndex]?.dataset?.rate) || 0; // Get the rate from the priority level
                const total = pages * rate; // Calculate total amount
                amountDisplay.textContent = `$${total.toFixed(2)}`; // Display in dollar format

                amountInput.value = total.toFixed(2);

            }

            // Attach event listeners
            pagesInput.addEventListener("input", calculateAmount);
            prioritySelect.addEventListener("change", calculateAmount);

            calculateAmount()

            // Initialize PayPal Buttons
            paypal.Buttons({
                createOrder: function(data, actions) {
                    const totalAmount = parseFloat(amountDisplay.textContent.replace("$", "")) || 0;
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: totalAmount.toFixed(2)
                            },
                        }],
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        alert(`Payment successful! Transaction ID: ${details.id}`);
                        // Perform further actions like updating the database.
                    });
                },
                onError: function(err) {
                    alert("Payment failed! Please try again.");
                }
            }).render("#paypal-button-container");
        });
    </script>





    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.10/dist/sweetalert2.all.min.js"></script>



</body>

</html>