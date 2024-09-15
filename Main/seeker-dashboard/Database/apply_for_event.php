<?php
session_start();
include '../Database/config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('User not logged in.');
            window.location.href = '../seeker/seekerlogin.html';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the event ID from POST request
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

// Validate event ID and user ID
if ($event_id <= 0 || $user_id <= 0) {
    echo "<script>
            alert('Invalid event or user ID.');
            window.history.back();
          </script>";
    exit();
}

// Check if the application already exists
$check_sql = "SELECT * FROM event_applications WHERE user_id = ? AND event_id = ?";
if ($check_stmt = $conn->prepare($check_sql)) {
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows > 0) {
        // Application already exists
        echo "<script>
                alert('You have already applied for this event.');
                window.history.back();
              </script>";
        $check_stmt->close();
        $conn->close();
        exit();
    }
    $check_stmt->close();
} else {
    echo "<script>
            alert('Error checking application status: " . addslashes($conn->error) . "');
            window.history.back();
          </script>";
    $conn->close();
    exit();
}

// Insert application into the database
$sql = "INSERT INTO event_applications (user_id, event_id) VALUES (?, ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $user_id, $event_id);
    if ($stmt->execute()) {
        // Close statements
        $stmt->close();
        
        // Redirect or show a success message
        echo "<script>
                alert('Application successful!');
                window.location.href = '../pages/events.php';
              </script>";
    } else {
        echo "<script>
                alert('Error applying for event: " . addslashes($stmt->error) . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Error preparing application query: " . addslashes($conn->error) . "');
            window.history.back();
          </script>";
}

$conn->close();
?>
