<?php
session_start();
include('includes/dbconnection.php');

// Include PHPMailer classes
require 'vendor/autoload.php';  // Ensure this points to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send the email
function sendEmail($recipient_email) {
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
        $mail->setFrom('support@expertsdom.com', 'Expertsdom Assistance');  // Sender's email
        $mail->addAddress($recipient_email);  // Recipient's email

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'Expertsdom Assignment Update';  // Subject of the email
        $mail->Body    = 'This is an automated mail to notify of your assignment update on Expertsdom.';  // HTML body content
        $mail->AltBody = 'Login to check work progress or results.';  // Plain text body content

        // Send the email
        if ($mail->send()) {
            echo 'Message sent successfully';
        }
    } catch (Exception $e) {
        // Output error message if sending fails
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Main code logic starts here
if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    // Get the recipient email from the assignments table
    $email_query = "SELECT email FROM assignments WHERE id = '$id'";
    $result = mysqli_query($con, $email_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $recipient_email = $row['email'];
    } else {
        echo json_encode(["status" => "error", "message" => "No email found for ID: $id"]);
        exit;
    }

    $work_is_done = mysqli_real_escape_string($con, $_POST['work_is_done']);
    $comments = isset($_POST['comments']) ? mysqli_real_escape_string($con, $_POST['comments']) : '';
    $amount_paid = isset($_POST['amount_paid']) ? floatval($_POST['amount_paid']) : 0;

    // Initialize update query
    $update_query = "UPDATE assignments SET 
                    work_is_done = '$work_is_done', 
                    comments = '$comments', 
                    amount_paid = '$amount_paid' 
                    WHERE id = '$id'";

    // Execute the update query
    if (mysqli_query($con, $update_query)) {
        $status = "success";
        $message = "Assignment updated successfully.";
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating assignment: " . mysqli_error($con)]);
        exit;
    }

    // Handle file upload if present
    if (isset($_FILES["work_results"]) && $_FILES["work_results"]["name"] != "") {
        $file = $_FILES["work_results"]["name"];
        // Replace spaces with underscores in the filename
        $file = str_replace(' ', '_', $file);

        // Optional: You can also sanitize the filename to remove other unwanted characters
        $file = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file);

        $result = mysqli_query($con, "SELECT work_results FROM assignments WHERE id = '$id'");
        $row = mysqli_fetch_assoc($result);
        $existing_files = $row['work_results'];

        // If there are existing files, append the new file with '@@@'
        if ($existing_files) {
            $new_files = $existing_files . '@@@' . $file;
        } else {
            $new_files = $file;
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES["work_results"]["tmp_name"], "uploads/" . $file)) {
            // Update the database with the new file names
            $file_update_query = "UPDATE assignments SET work_results = '$new_files' WHERE id = '$id'";

            if (mysqli_query($con, $file_update_query)) {
                $status = "success";
                $message = "File uploaded and assignment updated successfully.";

                // Call the sendEmail function to send the email
                if (function_exists('sendEmail')) {
                    sendEmail($recipient_email);
                }
            } else {
                $status = "error";
                $message = "Error saving file name in the database.";
            }
        } else {
            $status = "error";
            $message = "Error uploading the file.";
        }
    }

    echo json_encode(["status" => $status, "message" => $message]);
}
?>
