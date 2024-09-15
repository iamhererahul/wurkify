<!-- <link rel="stylesheet" href="../Database/config.php"> -->
<?php
session_start();
include('../Database/config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch user details from 'seekerauth' table
$sql = "SELECT username, profile_picture, email, twitter_username, facebook_username, linkedin_username, youtube_username 
        FROM seekerauth WHERE id = ?";
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
// Prepare SQL query to fetch user details
$sql = "SELECT username, email FROM seekerauth WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch user data
        $user = $result->fetch_assoc();
    } else {
        echo 'User not found';
        exit();
    }

    $stmt->close();
} else {
    echo 'Error fetching user details';
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
    <title>Plans</title>
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
            <h1>Plans</h1>
          </div>
          <div class="plans-boxes">
            <div class="plan-box">
              <div class="plan-title-container">
                <div class="plan-title">
                  <h2>Free</h2>
                  <p><span>$</span> 0.00</p>
                </div>
              </div>
              <ul>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Text Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Videos Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Appear On Leaderboard</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span>Browse Content Without Ads</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span> Access All Assignments</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span>Get Daily Prizes</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span>Earn Certificate</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span> GB Space For Hosting Files</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span>Access Badge System</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li><a href="/#">Join</a></li>
              </ul>
            </div>
            <div class="plan-box">
              <div class="plan-title-container">
                <div class="plan-title">
                  <h2>Basic</h2>
                  <p><span>$</span> 7.99</p>
                </div>
              </div>
              <ul>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Text Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Videos Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Appear On Leaderboard</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Browse Content Without Ads</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span> Access All Assignments</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Get Daily Prizes</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Earn Certificate</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span> GB Space For Hosting Files</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-xmark red"></i
                    ><span>Access Badge System</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li><a href="/#">Join</a></li>
              </ul>
            </div>
            <div class="plan-box">
              <div class="plan-title-container">
                <div class="plan-title">
                  <h2>Premium</h2>
                  <p><span>$</span> 19.99</p>
                </div>
              </div>
              <ul>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Text Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access All Videos Lessons</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Appear On Leaderboard</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Browse Content Without Ads</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span> Access All Assignments</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Get Daily Prizes</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Earn Certificate</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span> GB Space For Hosting Files</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li>
                  <div>
                    <i class="fa-solid fa-check"></i
                    ><span>Access Badge System</span>
                  </div>
                  <i class="fa-solid fa-circle-info help"></i>
                </li>
                <li><p>This Is Your Current Plan</p></li>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="../js/script.js"></script>
  </body>
</html>
