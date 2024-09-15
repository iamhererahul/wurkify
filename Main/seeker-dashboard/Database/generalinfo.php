<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include 'config.php'; // Adjust the path as needed

// Function to show alert and redirect
function showAlertAndRedirect($message, $redirectUrl) {
    echo '<script>alert("' . $message . '"); window.location.href="' . $redirectUrl . '";</script>';
    exit();
}

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    showAlertAndRedirect("User not logged in.", "generalinfo.php");
}
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $firstName = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
    $lastName = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
    $phoneNumber = filter_var(trim($_POST['phone_number']), FILTER_SANITIZE_STRING);
    $dob = filter_var(trim($_POST['dob']), FILTER_SANITIZE_STRING);
    $gender = filter_var(trim($_POST['gender']), FILTER_SANITIZE_STRING);
    $age = filter_var(trim($_POST['age']), FILTER_SANITIZE_NUMBER_INT);
    $country = filter_var(trim($_POST['country']), FILTER_SANITIZE_STRING);
    $state = filter_var(trim($_POST['state']), FILTER_SANITIZE_STRING);

    // Validate number inputs
    if (!is_numeric($phoneNumber) || !is_numeric($age)) {
        showAlertAndRedirect("Phone number and age must be numeric.", "generalinfo.php");
    }

    // Prepare an SQL statement for insertion or update (including user_id)
    $sql = "INSERT INTO user_general_info (user_id, first_name, last_name, phone_number, dob, gender, age, country, state) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
                first_name = VALUES(first_name), 
                last_name = VALUES(last_name), 
                phone_number = VALUES(phone_number), 
                dob = VALUES(dob), 
                gender = VALUES(gender), 
                age = VALUES(age), 
                country = VALUES(country), 
                state = VALUES(state)";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('issssssss', $user_id, $firstName, $lastName, $phoneNumber, $dob, $gender, $age, $country, $state);

        // Execute the statement
        if ($stmt->execute()) {
            showAlertAndRedirect("Data successfully saved!", "../pages/settings.php?success=1");
        } else {
            showAlertAndRedirect("Execute failed: " . $stmt->error, "generalinfo.php");
        }

        // Close the statement
        $stmt->close();
    } else {
        showAlertAndRedirect("Prepare failed: " . $conn->error, "generalinfo.php");
    }

    // Close the connection
    $conn->close();
} else {
    showAlertAndRedirect("Invalid request method.", "generalinfo.php");
}
?>
