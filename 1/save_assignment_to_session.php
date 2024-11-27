<?php
session_start();

// Get the raw POST data (assignment details)
$data = json_decode(file_get_contents('php://input'), true);

// Store assignment details in the session
if (isset($data['subject']) && isset($data['topic'])) {
    $_SESSION['assignment_details'] = $data; // Save all assignment details in session
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
