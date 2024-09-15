<?php
session_start();
include('config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in.'); window.location.href = '../pages/settings.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Get user_id from session

// Check if the POST data exists
if (isset($_POST['aadhar'], $_POST['pan'], $_POST['address_line1'], $_POST['address_line2'], $_POST['city'], $_POST['state'], $_POST['zipcode'])) {
    $aadhar = htmlspecialchars(trim($_POST['aadhar']));
    $pan = htmlspecialchars(trim($_POST['pan']));
    $address_line1 = htmlspecialchars(trim($_POST['address_line1']));
    $address_line2 = htmlspecialchars(trim($_POST['address_line2']));
    $city = htmlspecialchars(trim($_POST['city']));
    $state = htmlspecialchars(trim($_POST['state']));
    $zipcode = htmlspecialchars(trim($_POST['zipcode']));

  
    // Prepare SQL query to insert or update identification info
    $sql = "INSERT INTO identification_info (user_id, aadhar, pan, address_line1, address_line2, city, state, zipcode) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            aadhar = VALUES(aadhar), 
            pan = VALUES(pan), 
            address_line1 = VALUES(address_line1), 
            address_line2 = VALUES(address_line2), 
            city = VALUES(city), 
            state = VALUES(state), 
            zipcode = VALUES(zipcode)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssssss", $user_id, $aadhar, $pan, $address_line1, $address_line2, $city, $state, $zipcode);
        if ($stmt->execute()) {
            echo "<script>alert('Data successfully submitted.'); window.location.href = '../pages/settings.php';</script>";
        } else {
            echo "<script>alert('Error executing query: " . $stmt->error . "'); window.location.href = '../pages/settings.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing query: " . $conn->error . "'); window.location.href = '../pages/settings.php';</script>";
    }
} else {
    echo "<script>alert('Required form data is missing.'); window.location.href = '../pages/settings.php';</script>";
}

// Close the database connection
$conn->close();
?>
