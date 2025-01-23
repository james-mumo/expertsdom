<?php
// Include Composer's autoloader to load PHPMailer
require 'vendor/autoload.php';  // Ensure this points to your Composer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email on Button Click</title>
    <!-- Using Bootstrap CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Send Automated Email</h2>
        <form action="send_email.php" method="post">
            <button type="submit" name="sendEmail" class="btn btn-primary mt-3">Send Email</button>
        </form>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Display alert based on email sending status
        <?php if ($emailStatus === 'success'): ?>
            alert("Email sent successfully!");
        <?php elseif ($emailStatus === 'error'): ?>
            alert("Failed to send email. Please try again.");
        <?php endif; ?>
    </script>
</body>
</html>
