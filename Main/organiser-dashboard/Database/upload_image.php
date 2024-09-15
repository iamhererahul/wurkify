<?php
session_start();
include('config.php'); // Include your database connection file

// Initialize message variable
$message = '';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $image = $_FILES['profile_picture'];
    
    // Validate and upload image
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Define the upload directory
        $upload_dir = '../uploads/'; // Ensure this directory exists and is writable
        
        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
        }

        // Retrieve the current profile picture path from the database
        $sql = "SELECT profile_picture FROM `wurkify-user` WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($current_image_path);
            $stmt->fetch();
            $stmt->close();

            // Delete the old profile picture if it exists and is not the default placeholder
            if (!empty($current_image_path) && file_exists($current_image_path) && $current_image_path != $upload_dir . 'default_profile_picture.jpg') {
                unlink($current_image_path);
            }

            // Set the path for the new image
            $image_path = $upload_dir . basename($image['name']);

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                // Update the database with the new image path
                $sql = "UPDATE `wurkify-user` SET profile_picture = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $image_path, $user_id);
                    if ($stmt->execute()) {
                        $message = "Profile picture updated successfully.";
                    } else {
                        $message = "Error updating profile picture: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Error preparing SQL statement: " . $conn->error;
                }
            } else {
                $message = "Error moving uploaded file.";
            }
        } else {
            $message = "Error preparing SQL statement: " . $conn->error;
        }
    } else {
        $message = "File upload error: " . $image['error'];
    }
} else {
    $message = "No file uploaded or invalid request method.";
}

$conn->close();

// Redirect back to the profile page with a message
header('Location: ../pages/Profile.php?message=' . urlencode($message));
exit();
?>
