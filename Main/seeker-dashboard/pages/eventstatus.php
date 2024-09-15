<?php
session_start();
include('../Database/config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from 'wurkify-user' table
$sql = "SELECT username, profile_picture, email FROM `wurkify-user` WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        handle_error('Error executing user query: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Set default profile picture if not set
        $profile_picture = $user['profile_picture'] ? '../uploads/' . $user['profile_picture'] : '../default.jpeg';
        
        // Add profile picture to user data
        $user['profile_picture'] = $profile_picture;
    } else {
        handle_error('User not found');
    }
    $stmt->close();
} else {
    handle_error('Error preparing user details query: ' . $conn->error);
}
// Fetch events that the user has applied for
$sql = "SELECT e.event_name, e.event_date, e.shift_time, e.dress_code, e.dress_code_desc, 
               e.payment_amount, e.clearance_days, e.work, e.location, e.required_members, e.note, e.status
        FROM organiser_event_registration e
        JOIN event_applications ea ON e.user_id = ea.event_id
        WHERE ea.user_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    handle_error('Error fetching applied events: ' . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/all.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&amp;display=swap"
        rel="stylesheet"
    />
    <title>Applied Events</title>
</head>
<body>
<div class="page-content">
    <div class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-xmark xmark"></i>
            <h3><?php echo htmlspecialchars($user['username']); ?></h3>
        </div>
        <ul>
            <li>
                <a href="../index.php" class="sidebar-link">
                    <i class="fa-solid fa-tachometer-alt fa-fw"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="./Profile.php" class="sidebar-link">
                    <i class="fa-solid fa-user fa-fw"></i><span>Profile</span>
                </a>
            </li>
            <li>
                <a href="./events.php" class="sidebar-link">
                    <i class="fa-solid fa-calendar-day fa-fw"></i><span>Events</span>
                </a>
            </li>
            <li>
                <a href="./eventstatus.php" class="sidebar-link">
                    <i class="fa-solid fa-calendar-check fa-fw"></i><span>Event Status</span>
                </a>
            </li>
            <li>
                <a href="./Payment Status.php" class="sidebar-link">
                    <i class="fa-solid fa-credit-card fa-fw"></i><span>Payment Status</span>
                </a>
            </li>
            <li>
                <a href="./pricing.php" class="sidebar-link">
                    <i class="fa-solid fa-tags fa-fw"></i><span>Pricing</span>
                </a>
            </li>
            <li>
                <a href="./feedback.php" class="sidebar-link">
                    <i class="fa-solid fa-comment-dots fa-fw"></i><span>Feedback</span>
                </a>
            </li>
            <li>
                <a href="./settings.php" class="sidebar-link">
                    <i class="fa-solid fa-cog fa-fw"></i><span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    <main>
        <div class="header">
            <i class="fa-solid fa-bars bar-item"></i>
            <div class="search">
                <input type="search" placeholder="Type A Keyword" />
            </div>
            <div class="profile">
                <span class="bell"><i class="fa-regular fa-bell fa-lg"></i></span>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="No Image" style="border-radius: 50%;" />
            </div>
        </div>
        <div class="main-content">
            <div class="title">
                <h1>Applied Events</h1>
            </div>
            <div class="courses-boxes">
                <?php foreach ($events as $event): ?>
                <div class="courses-box">
                   
                    <div class="courses-card-body">
                        <h4><?php echo htmlspecialchars($event['event_name']); ?></h4>
                        <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p>Shift Time: <?php echo htmlspecialchars($event['shift_time']); ?></p>
                        <p>Dress Code: <?php echo htmlspecialchars($event['dress_code']); ?></p>
                        <?php if ($event['dress_code_desc']): ?>
                        <p>Dress Code Description: <?php echo htmlspecialchars($event['dress_code_desc']); ?></p>
                        <?php endif; ?>
                        <p>Payment Amount: <?php echo htmlspecialchars($event['payment_amount']); ?></p>
                        <p>Clearance Days: <?php echo htmlspecialchars($event['clearance_days']); ?></p>
                        <p>Work: <?php echo htmlspecialchars($event['work']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                        <p>Required Members: <?php echo htmlspecialchars($event['required_members']); ?></p>
                        <p>Note: <?php echo htmlspecialchars($event['note']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($event['status']); ?></p>
                    </div>
                    <div class="courses-card-footer">
                        <span><i class="fa-regular fa-user"></i>Applied</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<script src="../js/script.js"></script>
</body>
</html>
