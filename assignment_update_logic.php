<?php
include "includes/dbconnection.php"; // Ensure $con is properly set

// Include PHPMailer classes
require 'vendor/autoload.php';  // Ensure this points to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!$con) {
    die("Database connection failed.");
}

// Function to send the email
function sendEmail($recipient_email)
{
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
            echo '';
        }
    } catch (Exception $e) {
        // Output error message if sending fails
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}





if (isset($_POST['update'])) {
    $assignment_id = $_POST['assignment_id'];
    $amount_paid = $_POST['amount_paid'];
    $work_is_done = isset($_POST['work_is_done']) ? $_POST['work_is_done'] : 0;
    $file_path = "";

    $id = mysqli_real_escape_string($con, $_POST['assignment_id']);

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

    // Handle file upload if present
    if (isset($_FILES["work_results"]) && $_FILES["work_results"]["name"] != "") {
        $file = $_FILES["work_results"]["name"];
        // Sanitize filename
        $file = str_replace(' ', '_', $file);
        $file = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file);

        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . $file;

        // Fetch existing file records
        $result = mysqli_query($con, "SELECT work_results FROM assignments WHERE id = '$assignment_id'");
        $row = mysqli_fetch_assoc($result);
        $existing_files = $row['work_results'];

        // Append new file if there are existing ones
        if ($existing_files) {
            $new_files = $existing_files . '@@@' . $file_path;
        } else {
            $new_files = $file_path;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES["work_results"]["tmp_name"], $file_path)) {
            // Update file in database
            $file_update_query = "UPDATE assignments SET work_results = '$new_files' WHERE id = '$assignment_id'";

            if (mysqli_query(
                $con,
                $file_update_query
            )) {
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

            echo "Assignment Details and File(s) Updated Succesfully!";
        } else {
            die("Error uploading the file.");
        }
    }

    // Update query for assignment details
    $query = "UPDATE assignments SET amount_paid = ?, work_is_done = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("dii", $amount_paid, $work_is_done, $assignment_id);

    if ($stmt->execute()) {
        echo "Assignment updated successfully.";
        // header("Location: assignments_list.php");

        exit();
    } else {
        echo "Error updating assignment: " . $stmt->error;
    }

    // Redirect back to assignments page

    $stmt->close();
    $con->close();
    exit();
}
