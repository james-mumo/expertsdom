<?php
session_start();
include('includes/dbconnection.php');
$permission = $_SESSION['permission']; // Assuming this is set when the user logs in

require 'vendor/autoload.php';  // Ensure this points to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch assignment details from the database if a view request is made
if (isset($_POST['view_id'])) {
    $id = $_POST['view_id'];
    $query = mysqli_query($con, "SELECT * FROM assignments WHERE id = '$id'");
    $row = mysqli_fetch_array($query);
    
    // Calculate remaining time for the deadline
    $deadline = new DateTime($row['deadline']);
    $current_time = new DateTime();
    $interval = $current_time->diff($deadline);
    $remaining_time = $interval->format('%d days, %h hours');





$emailStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendEmail'])) {
    // Create PHPMailer instance
// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();  // Use SMTP for sending email
    $mail->Host = 'mail.expertsdom.com';  // SMTP server
    $mail->SMTPAuth = true;  // Enable SMTP authentication
    $mail->Username = 'support@expertsdom.com';  // SMTP username (use the full email)
    $mail->Password = 'Support@4321';  // SMTP password (replace with your password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use STARTTLS encryption
    $mail->Port = 587;  // SMTP port (usually 587 for STARTTLS)

    // Recipients
    $mail->setFrom('support@expertsdom.com', 'Mailer');  // Sender's email
    $mail->addAddress('98mumo@gmail.com');  // Recipient's email

    // Content
    $mail->isHTML(true);  // Set email format to HTML
    $mail->Subject = 'Automated Email';  // Subject of the email
    $mail->Body    = 'This is an automated email sent via PHPMailer.';  // HTML body content
    $mail->AltBody = 'This is the plain text version of the email content.';  // Plain text body content

    // Send the email
    if ($mail->send()) {
        echo 'Message sent successfully';
    }
} catch (Exception $e) {
    // Output error message if sending fails
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}

}
 
?>




<div style="background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 10px; max-width: 900px; margin: 20px auto;">
    <h4 style="text-align: center; font-size: 26px; color: teal; font-weight: bold; margin-bottom: 10px;">Assignment Details</h4>

    <!-- Subject and Topic -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="flex: 1; padding-right: 10px;">
            <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Subject:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
    <?php echo htmlentities($row['subject']); ?>
</p>

        </div>
        <div style="flex: 1; padding-left: 10px;">
            <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Topic:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['topic']); ?></p>
        </div>
    </div>
    
    <!-- Instructions -->
    <div style="margin-bottom: 20px;">
        <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Instructions:</p>
        <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
    <?php echo htmlentities($row['instructions']); ?>
</p>

    </div>

    <!-- 3 Columns: Resources, Format, Pages -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="flex: 1; padding-right: 10px;">
            <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Resources:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['resources']); ?></p>
        </div>
        <div style="flex: 1; padding-left: 10px;">
            <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Format:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['format']); ?></p>
        </div>
        <div style="flex: 1; padding-left: 10px;">
            <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Pages:</p>
            <p style="font-size: 16px; color: #555; background-color: #f7f7f7; padding: 5px; border-radius: 4px;"><?php echo htmlentities($row['pages']); ?></p>
        </div>
    </div>

    <!-- Deadline, Time Remaining, and File Information -->
   <div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; font-size: 16px; color: #555;">
        <div style="width: 50%; padding-right: 10px; border-right: 1px solid #ccc;">
            <p style=""><strong style="color: #333;">Deadline:</strong> 
            <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">

                <?php 
            // Assuming $row['deadline'] is in a standard date format (e.g., 'YYYY-MM-DD')
            $deadline = $row['deadline'];
            $formatted_date = date('d F Y', strtotime($deadline)); // d for day, F for full month name, Y for year
            echo htmlentities($formatted_date); 
            ?>
            </span>
            </p>

            <p><strong style="color: #333;"> Time Remaining:</strong><span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;"> <?php echo $remaining_time; ?></span></p>
        </div>
        <div style="width: 50%; padding-left: 10px; word-wrap: break-word; overflow-wrap: break-word;">

            <p><strong style="color: #333;">Submitted On:</strong> 
            <span style="background-color: #f7f7f7; padding: 5px; border-radius: 4px;">
            <?php
            // Assuming $row['submitted_on'] is a valid datetime
            $submitted_on = $row['submitted_on'];

            // Format the datetime (Day, Month Date, Year - Time AM/PM)
            echo date('F j, Y - h:i A', strtotime($submitted_on));
            ?>

</span>
            </p>

            <p  style="background-color: #f7f7f7; padding: 10px; border-radius: 4px;">
            <strong style="color: #333;">File(s) Attached:</strong> 
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


    <h4 style="text-align: center; font-size: 22px; color: teal; font-weight: bold; margin-bottom: 10px;">Work Results</h4>
    <div style="display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
        <form method="POST" action="" enctype="multipart/form-data">

             <p>
    <strong style="color: #333;">Work Results Submitted:</strong>
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
            echo ($index + 1) . ". <a href='" . $file_path . "' target='_blank'>" . htmlentities($file) . "</a><br>";
        }
    } else {
        echo 'None';
    }
    ?>
</p>

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

            
        </form>


        
    </div>

    <div style="margin-bottom: 20px;">
        <label for="comments" style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 10px;">Comments to Student:</label>
        <textarea id="comments" style="padding: 10px; font-size: 14px; width: 100%; height: 120px; border-radius: 4px; border: 1px solid #ddd;"><?php echo htmlentities($row['comments']); ?></textarea>
    </div>

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
