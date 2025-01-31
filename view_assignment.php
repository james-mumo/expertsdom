<?php
session_start();
include('includes/dbconnection.php');
$permission = $_SESSION['permission']; // Assuming this is set when the user logs in



// Fetch assignment details from the database if a view request is made
if (isset($_POST['view_id'])) {
    $view_id = $_POST['view_id'];
    $query = mysqli_query($con, "SELECT * FROM assignments WHERE id = '$view_id'");

    $row = mysqli_fetch_array($query);

    // Calculate remaining time for the deadline
    $deadline = new DateTime($row['deadline']);
    // $order_amount = $row(['order_amount']);
    $current_time = new DateTime();
    $interval = $current_time->diff($deadline);
    $remaining_time = $interval->format('%d days, %h hours');
?>





    <div style="background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 10px; max-width: 900px; margin: 20px auto;">
        <h4 style="text-align: center; font-size: 26px; color: teal; font-weight: bold; margin-bottom: 10px;">Assignment Details</h4>

        <!-- Subject and Topic -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <div style="flex: 1; padding-right: 10px;">
                <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Subject:</p>
                <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                    <?php echo htmlentities($row['subject']); ?>
                </p>

            </div>
            <div style="flex: 1; padding-left: 10px;">
                <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Topic:</p>
                <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['topic']); ?></p>
            </div>
        </div>

        <!-- Instructions -->
        <div style="margin-bottom: 20px;">
            <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Instructions:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                <?php echo htmlentities($row['instructions']); ?>
            </p>

        </div>

        <!-- 3 Columns: Resources, Format, Pages -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <div style="flex: 1; padding-right: 10px;">
                <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Resources:</p>
                <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['resources']); ?></p>
            </div>
            <div style="flex: 1; padding-left: 10px;">
                <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Format:</p>
                <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['format']); ?></p>
            </div>
            <div style="flex: 1; padding-left: 10px;">
                <p style="font-size: 18px; font-weight: bold; color: #00796b; margin-bottom: 10px;">Pages:</p>
                <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['pages']); ?></p>
            </div>
        </div>

        <!-- Deadline, Time Remaining, and File Information -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 16px; color: #555;">
                <div style="width: 50%; padding-right: 10px; border-right: 1px solid #ccc;">
                    <p><strong style="color: #00796b;">Deadline:</strong>
                        <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">

                            <?php
                            // Assuming $row['deadline'] is in a standard date format (e.g., 'YYYY-MM-DD')
                            $deadline = $row['deadline'];
                            $formatted_date = date('d F Y', strtotime($deadline)); // d for day, F for full month name, Y for year
                            echo htmlentities($formatted_date);
                            ?>
                        </span>
                    </p>

                    <p><strong style="color: #00796b;"> Time Remaining:</strong><span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;"> <?php echo $remaining_time; ?></span></p>

                    <p style="font-size: 1rem; color: #00796b; margin: 5px 0;">
                        <span style="font-weight: bold;">Amount to Pay:</span>
                        <span style="color: #388e3c;">$<?php echo $row['order_amount']; ?></span>
                    </p>
                </div>
                <div style="width: 50%; padding-left: 10px; word-wrap: break-word; overflow-wrap: break-word;">

                    <p><strong style="color: #00796b;">Submitted On:</strong>
                        <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
                            <?php
                            // Assuming $row['submitted_on'] is a valid datetime
                            $submitted_on = $row['submitted_on'];

                            // Format the datetime (Day, Month Date, Year - Time AM/PM)
                            echo date('F j, Y - h:i A', strtotime($submitted_on));
                            ?>

                        </span>
                    </p>

                    <p style="background-color: #f7f7f7; padding: 10px; border-radius: 4px;">
                        <strong style="color: #00796b;">File(s) Attached:</strong>
                        <br>
                        <?php
                        // Check if there are files attached
                        if ($row['file']) {
                            // Split the filenames by '@'
                            $files = explode('@@@', $row['file']);

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
        </div>

        <?php
        // Assuming the amount is fetched from the database

        $paypalEmail = "wmuasya007@gmail.com"; // PayPal email
        ?>

        <?php
        // Check if there are work results (files)
        if ($row['work_results']) {
        ?>
            <div class="col mt-4 d-flex align-items-center justify-content-between"
                style="background-color: #c8e6c9; border: 2px solid #00796b; padding: 20px; border-radius: 10px; font-family: Arial, sans-serif;">

                <!-- Payment Information -->
                <div style="flex-grow: 1;">
                    <h4 style="font-size: 1.0rem; color: #00796b; font-weight: bold;" class="mb-3">Kindly Pay and Download the Attached Work Results</h4>
                    <p style="font-size: 1rem; color: #00796b; margin: 5px 0;">
                        <span style="font-weight: bold;">Paypal Email:</span>
                        <span style="color: #388e3c;"><?php echo $paypalEmail; ?></span>
                    </p>
                    <p style="font-size: 1rem; color: #00796b; margin: 5px 0;">
                        <span style="font-weight: bold;">Amount to Pay:</span>
                        <span style="color: #388e3c;">$<?php echo $row['order_amount']; ?></span>
                    </p>
                </div>

                <!-- PayPal Icon and Link -->
                <a href="#" class="btn btn-primary d-flex align-items-center"
                    style="background-color: #00796b; color: white; padding: 10px 15px; border-radius: 5px; font-size: 1rem; text-decoration: none; display: flex; align-items: center;">
                    <i class="fab fa-paypal" style="font-size: 1.8rem; margin-right: 10px;"></i>
                    <span>Pay Now with PayPal</span>
                </a>
            </div>
        <?php
        } // End of if statement for checking if work_results exist
        ?>




        <h4 style="text-align: center; font-size: 22px; color: teal; font-weight: bold; margin-bottom: 2px;  margin-top: 10px; ">Work Results</h4>
        <div style="display: flex; justify-content: space-between; margin-top: 7px; padding-top: 7px; border-top: 1px solid #ddd; width: 100%; border:#28a745">

            <form method="POST" action="" enctype="multipart/form-data" style="width: 100%;">

                <p style="font-family: Arial, sans-serif; color: #00796b;">
                    <strong style="color: #388e3c; font-size: 1.1rem;">Work Results Submitted:</strong>
                    <br>
                    <?php
                    // Check if there are files attached
                    if ($row['work_results']) {
                        // Split the filenames by '@'
                        $files = explode('@@@', $row['work_results']);

                        // Loop through the files and display them with a number
                        foreach ($files as $index => $file) {
                            // Clean the file name
                            $file = trim($file); // Remove any unwanted spaces
                            $file_path = 'uploads/' . htmlentities($file);
                    ?>
                <div style="background-color: #e8f5e9; padding: 10px; border-radius: 5px; margin-bottom: 10px; display: flex; flex-direction: column; align-items: flex-start; width: 100%;">
                    <!-- File Name and Link -->
                    <div style="margin-bottom: 10px; overflow-wrap: break-word; word-wrap: break-word; max-width: 100%;">
                        <a href="<?php echo $file_path; ?>" target="_blank" style="color: #388e3c; font-size: 1rem; text-decoration: none; font-weight: bold;">
                            <?php echo ($index + 1) . ". " . htmlentities($file); ?>
                        </a>
                    </div>

                    <!-- Download Button -->
                    <a href="<?php echo $file_path; ?>" download
                        style="background-color: #388e3c; color: white; padding: 5px 15px; border-radius: 5px; font-size: 0.9rem; text-decoration: none; display: inline-block;">
                        Download
                    </a>
                </div>

        <?php
                        }
                    } else {
                        echo '<div style="color: #f44336; background-color:#e8f5e9; padding:4px; width:100%; border-radius: 5px; font-size: 1.3rem;">None</div>';
                    }
        ?>
        </p>


        <?php if ($permission === 'User') { ?>
            <div style="margin-bottom: 20px; flex:1;">
                <label for="comments" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Comments from Tutor:</label>
                <textarea id="comments" style="padding: 10px; font-size: 14px; width: 100%; height: 120px; border-radius: 4px; border: 1px solid #ddd;"><?php echo htmlentities($row['comments']); ?></textarea>
            </div>
        <?php } ?>

        <?php if ($permission === 'Admin' || $permission === 'Super User') { ?>

            <div style="margin-bottom: 20px;">
                <label for="work_results" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Attach Work Results:</label>
                <input type="file" name="work_results" accept=".pdf,.doc,.docx" id="work_results" style="padding: 5px; font-size: 14px; width: 100%; border-radius: 4px; border: 1px solid #ddd;" />
            </div>


            <div style="flex: 1; padding-left: 20px; display: flex; align-items: center;">
                <input
                    type="checkbox"
                    id="work_done"
                    data-id="<?php echo $row['id']; ?>"
                    <?php echo ($row['work_is_done'] ? 'checked' : ''); ?>
                    style="margin: 0 10px 0 0; vertical-align: middle;">
                <label
                    for="work_done"
                    style="font-size: 20px; font-weight: bold; color: teal; cursor: pointer; margin: 0;">
                    Mark as Done
                </label>
            </div>
        </div>

        <?php
            // Check if comments are not null or empty
            if (!is_null($row['comments'])) {
        ?>
            <div style="margin-bottom: 20px;">
                <label for="comments" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Comments to Student:</label>
                <textarea id="comments" style="padding: 10px; font-size: 14px; width: 100%; height: 120px; border-radius: 4px; border: 1px solid #ddd;"><?php echo htmlentities($row['comments']); ?></textarea>
            </div>
        <?php
            }
        ?>



        </form>

        <div style="text-align: center; margin-top: 20px;">
            <button type="button" class="btn" id="delete_assignment" data-id="<?php echo $row['id']; ?>" style="padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 4px; background-color: #dc3545; color: white;">
                Delete Assignment
            </button>
            <button type="button" class="btn" id="save_changes" data-id="<?php echo $row['id']; ?>" style="padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 4px; background-color: #28a745; color: white;">
                Save Changes
            </button>
        </div>

    <?php } ?>






    </div>


<?php
}
?>