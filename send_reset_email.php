<?php
header('Content-Type: application/json');

// Decode the incoming JSON payload
$data = json_decode(file_get_contents('php://input'), true);

// Check if email is provided
if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $data['email'];

    $subject = "Reset Your Password";
    $message = "Please click this link to reset your password: https://www.youtube.com/watch?v=T4ne2Z7nYQ0";
    $headers = "From: noreply@kada.com";

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Email sending failed.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
}
