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
    <title>Friends</title>
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
            <h1>Friends</h1>
          </div>
          <div class="friends-main-boxes">
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-01.jpg" alt="" />
                <h4>Ahmed Nasser</h4>
                <p>JavaScript Developer</p>
              </div>
              <div class="friends-box-card-body">
                <h1>VIP</h1>
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>99 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>15 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>25 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/10/2021</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-02.jpg" alt="" />
                <h4>Omar Fathy</h4>
                <p>Cloud Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>30 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>11 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>12 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/08/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-03.jpg" alt="" />
                <h4>Omar Ahmed</h4>
                <p>Mobile Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>80 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>20 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/06/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-04.jpg" alt="" />
                <h4>Shady Nabil</h4>
                <p>Back-End Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>70 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>30 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 28/06/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-05.jpg" alt="" />
                <h4>Mohamed Ibrahim</h4>
                <p>Algorithm Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>80 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>30 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 28/08/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-04.jpg" alt="" />
                <h4>Amr Hendawy</h4>
                <p>Back-End Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>70 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>30 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 28/06/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-02.jpg" alt="" />
                <h4>Mahmoud Adel</h4>
                <p>Cloud Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>30 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>11 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>12 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/08/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-01.jpg" alt="" />
                <h4>Gareeb Elshiekh</h4>
                <p>JavaScript Developer</p>
              </div>
              <div class="friends-box-card-body">
                <h1>VIP</h1>
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>99 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>15 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>25 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/10/2021</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-05.jpg" alt="" />
                <h4>Ahmed Abuzaid</h4>
                <p>Algorithm Developer</p>
              </div>
              <div class="friends-box-card-body">
                <h1>VIP</h1>
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>80 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>30 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 28/08/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
            <div class="friends-box-card">
              <div class="friends-box-card-contact">
                <i class="fa-solid fa-phone"></i>
                <i class="fa-regular fa-envelope"></i>
              </div>
              <div class="friends-box-card-info">
                <img src="../images/friend-03.jpg" alt="" />
                <h4>Hamza</h4>
                <p>Front-End Developer</p>
              </div>
              <div class="friends-box-card-body">
                <ul>
                  <li>
                    <i class="fa-regular fa-face-smile"></i
                    ><span>80 Friends</span>
                  </li>
                  <li>
                    <i class="fa-solid fa-code-commit"></i
                    ><span>20 Projects</span>
                  </li>
                  <li>
                    <i class="fa-regular fa-newspaper"></i
                    ><span>18 Articles</span>
                  </li>
                </ul>
              </div>
              <div class="friends-box-card-footer">
                <p>Joined 02/06/2020</p>
                <div class="friends-box-card-footer-buttons">
                  <a href="/#">Profile</a>
                  <a href="/#">Remove</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="../js/script.js"></script>
  </body>
</html>
