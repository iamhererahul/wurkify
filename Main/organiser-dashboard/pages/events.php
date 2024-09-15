<?php
session_start();
include('../Database/config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare SQL query to fetch user details
$sql = "SELECT username, profile_picture, email FROM `wurkify-user` WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        handle_error('Error executing user query: ' . $stmt->error);
        exit();
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
        exit();
    }
    $stmt->close();
} else {
    handle_error('Error preparing user details query: ' . $conn->error);
    exit();
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
    <title>Projects</title>
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
            <h1>Projects</h1>
          </div>

          <div class="main-content-boxes projects-main-content-boxes">
           
          <div class="box">
  <div class="box-section1">
    <div class="box-title">
      <h2>Event Registration Form</h2>
      <p>Submit the details of your event</p>
    </div>
  </div>
  <div class="general-info-section2">
    <form action="../Database/event_register.php" method="post">
    <label for="event_name" style="display: block; margin-bottom: 5px;">Event Name</label>
    <input type="text" name="event_name" id="event_name" placeholder="Enter event name" 
           style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
           required />
           
      <label for="event_date" style="display: block; margin-bottom: 5px;">Event Date</label>
      <input type="date" name="event_date" id="event_date" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required />

      <label for="shift_time" style="display: block; margin-bottom: 5px;">Shift Time</label>
      <input type="text" name="shift_time" id="shift_time" placeholder="Enter shift time" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required />

      <label for="dress_code" style="display: block; margin-bottom: 5px;">Dress Code</label>
      <select name="dress_code" id="dress_code" onchange="toggleDressCodeDetails()" 
              style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
              required>
        <option value="" disabled selected>Is there a dress code?</option>
        <option value="No">No</option>
        <option value="Yes">Yes</option>
      </select>

      <div id="dress_code_details" style="display:none;">
        <label for="dress_code_desc" style="display: block; margin-bottom: 5px;">If Yes, describe</label>
        <input type="text" name="dress_code_desc" id="dress_code_desc" placeholder="Describe the dress code" 
               style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" />
      </div>

      <label for="payment_amount" style="display: block; margin-bottom: 5px;">Payment Amount</label>
      <input type="number" name="payment_amount" id="payment_amount" placeholder="Enter payment amount" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required oninput="validateNumber(this)" />

      <label for="clearance_days" style="display: block; margin-bottom: 5px;">Payment Clearance (Days)</label>
      <input type="number" name="clearance_days" id="clearance_days" placeholder="Enter days for payment clearance" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required oninput="validateNumber(this)" />

      <label for="work" style="display: block; margin-bottom: 5px;">Work</label>
      <input type="text" name="work" id="work" placeholder="Enter work description" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required />

      <label for="location" style="display: block; margin-bottom: 5px;">Location</label>
      <input type="text" name="location" id="location" placeholder="Select location" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required />
     

      <label for="required_members" style="display: block; margin-bottom: 5px;">Required Member Count</label>
      <input type="number" name="required_members" id="required_members" placeholder="Enter required members count" 
             style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;" 
             required oninput="validateNumber(this)" />

      <label for="note" style="display: block; margin-bottom: 5px;">Additional Notes</label>
      <textarea name="note" id="note" placeholder="Enter any additional details" rows="4" 
                style="background: white;width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-bottom: 10px;"></textarea>

      <input type="submit" value="Submit Event" 
             style="background-color: #0075ff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;" />
    </form>
  </div>
</div>
      </main>
    </div>
    <script src="../js/script.js"></script>
    <script src="../js/dresscode.js"></script>
  </body>
</html>
