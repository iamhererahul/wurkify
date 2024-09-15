<?php
session_start();
include '../Database/config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in.'); window.location.href = '../seeker/seekerlogin.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from '`wurkify-user`' table
$sql = "SELECT username, profile_picture, email FROM `wurkify-user` WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die('Error executing user query: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Set default profile picture if not set
        $profile_picture = $user['profile_picture'] ? '../uploads/' . $user['profile_picture'] : '../default.jpeg';
        
        // Add profile picture to user data
        $user['profile_picture'] = $profile_picture;
    } else {
        die('User not found');
    }
    $stmt->close();
} else {
    die('Error preparing user details query: ' . $conn->error);
}

// Fetch all events with organizer details (updated columns)
$sql = "SELECT id,event_name, event_date, shift_time, dress_code, dress_code_desc, clearance_days, work, note, payment_amount, location, required_members, status
        FROM organiser_event_registration
        ORDER BY CASE status
            WHEN 'Pending' THEN 1
            WHEN 'Confirmed' THEN 2
            ELSE 3
        END";

if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $events = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $events = [];
    }
    $stmt->close();
} else {
    die('Error preparing events query: ' . $conn->error);
}

// Close the database connection
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
    <title>Courses</title>
  </head>
<style>
        .main-content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .title h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .events-boxes {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-around;
        }
        .event-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #fff;
            padding: 20px;
            width: 100%;
            max-width: 350px;
            position: relative;
            margin: 10px;
        }
        .event-card-body p {
            margin: 5px 0;
        }
        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }
        .status-confirmed {
            color: #27ae60;
            font-weight: bold;
        }
        .status-default {
            color: #000;
            font-weight: bold;
        }
        .apply-button {
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin-top: 10px;
        }
        .apply-button:hover {
            background-color: #218838;
        }
    </style>
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
        <h1>Event Status</h1>
    </div>
    <div class="events-boxes">
    <?php if (count($events) > 0): ?>
        <?php foreach ($events as $event): ?>
            <div class="event-box">
                <div class="event-card-body">
                    <!-- Event details -->
                    <h4><?php echo htmlspecialchars($event['event_name']); ?></h4>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                    <p><strong>Shift Time:</strong> <?php echo htmlspecialchars($event['shift_time']); ?></p>
                    <p><strong>Dress Code:</strong> <?php echo htmlspecialchars($event['dress_code']); ?></p>
                    <p><strong>Dress Code Description:</strong> <?php echo htmlspecialchars($event['dress_code_desc']); ?></p>
                    <p><strong>Clearance Day:</strong> <?php echo htmlspecialchars($event['clearance_days']); ?></p>
                    <p><strong>Work:</strong> <?php echo htmlspecialchars($event['work']); ?></p>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($event['note']); ?></p>
                    <p><strong>Payment Amount:</strong> <?php echo htmlspecialchars($event['payment_amount']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                    <p><strong>Required Members:</strong> <?php echo htmlspecialchars($event['required_members']); ?></p>
                    <p><strong>Status:</strong> 
                        <?php
                        $status = htmlspecialchars($event['status']);
                        if ($status === 'Pending'): ?>
                            <span class="status-pending">
                                <?php echo $status; ?>
                            </span>
                        <?php elseif ($status === 'Confirmed'): ?>
                            <span class="status-confirmed">
                                <?php echo $status; ?>
                            </span>
                        <?php else: ?>
                            <span class="status-default">
                                <?php echo $status; ?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                <form id="apply-form-<?php echo htmlspecialchars($event['id']); ?>" action="../Database/apply_for_event.php" method="post">
    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
    <button type="button" onclick="confirmApply('<?php echo htmlspecialchars($event['id']); ?>')" class="apply-button">
        Apply
    </button>
</form>



            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
</div>
</div>
        </main>
        <script>
function confirmApply(eventId) {
    if (confirm('Are you sure you want to apply for this event?')) {
        document.getElementById('apply-form-' + eventId).submit();
    }
}
</script>
</body>
</html>
