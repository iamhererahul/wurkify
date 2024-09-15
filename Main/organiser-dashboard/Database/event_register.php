<?php
// Include the config file for the database connection
include 'config.php';

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in.'); window.location.href = '../seeker/seekerlogin.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Sanitize and retrieve form data
$event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_STRING);
$event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
$shift_time = filter_input(INPUT_POST, 'shift_time', FILTER_SANITIZE_STRING);
$dress_code = filter_input(INPUT_POST, 'dress_code', FILTER_SANITIZE_STRING);
$dress_code_desc = isset($_POST['dress_code_desc']) ? filter_input(INPUT_POST, 'dress_code_desc', FILTER_SANITIZE_STRING) : null;
$payment_amount = filter_input(INPUT_POST, 'payment_amount', FILTER_VALIDATE_FLOAT);
$clearance_days = filter_input(INPUT_POST, 'clearance_days', FILTER_VALIDATE_INT);
$work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_STRING);
$location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
$required_members = filter_input(INPUT_POST, 'required_members', FILTER_VALIDATE_INT);
$note = isset($_POST['note']) ? filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING) : null;

// Validate required fields
if (empty($event_name) || empty($event_date) || empty($shift_time) || empty($payment_amount) || empty($clearance_days) || empty($work) || empty($location) || empty($required_members)) {
    echo "<script>alert('All required fields must be filled.'); window.history.back();</script>";
    exit();
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO organiser_event_registration (user_id, event_name, event_date, shift_time, dress_code, dress_code_desc, payment_amount, clearance_days, work, location, required_members, note, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
$stmt->bind_param("isssssssisss", $user_id, $event_name, $event_date, $shift_time, $dress_code, $dress_code_desc, $payment_amount, $clearance_days, $work, $location, $required_members, $note);

// Execute the statement and handle success or error
if ($stmt->execute()) {
    echo "<script>alert('Event registered successfully!'); window.location.href = '../pages/events.php';</script>";
} else {
    echo "<script>alert('Error occurred: " . addslashes($stmt->error) . "'); window.history.back();</script>";
}

// Close connections
$stmt->close();
$conn->close();
?>
