<?php
// Include database configuration file
require 'config.php';

// Start session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $usernameOrEmail = $_POST['usernameOrEmail'];
    $password = $_POST['password'];

    // SQL query to check if the user exists by username or email
    $query = "SELECT * FROM `wurkify-user` WHERE (username = ? OR email = ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, now check the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] == 'seeker') {
                echo "<script>alert('Login successful! Redirecting to Dashboard.'); window.location.href = '../seeker-dashboard/index.php';</script>";
            } elseif ($user['role'] == 'organizer') {
                echo "<script>alert('Login successful! Redirecting to Organizer Dashboard.'); window.location.href = '../organiser-dashboard/index.php';</script>";
            }
        } else {
            // Invalid password
            echo "<script>alert('Invalid password! Please try again.'); window.history.back();</script>";
        }
    } else {
        // No user found with the given username/email
        echo "<script>alert('No account found with the given credentials.'); window.history.back();</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
