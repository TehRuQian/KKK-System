<?php
$to = "test@example.com";  // Use a real email for testing
$subject = "Test Email";
$message = "This is a test email.";
$headers = "From: no_reply@kada.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Email failed to send.";
}
?>