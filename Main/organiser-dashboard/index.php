<?php
session_start();
include('./Database/config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT username, profile_picture,email FROM `wurkify-user` WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Set default profile picture if not set
        $profile_picture = $user['profile_picture'] ? '../uploads/' . $user['profile_picture'] : '../default.jpeg';
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
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="" />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&amp;display=swap"
      rel="stylesheet"
    />
    <title>Dashboard</title>
  </head>
  <body>
    <div class="loader">
      <h1>Loading<span>....</span></h1>
    </div>
    <div class="page-content index-page">
      <div class="sidebar">
        <div class="brand">
          <i class="fa-solid fa-xmark xmark"></i>
          <h3 class="brand-name">
            Wurkify
          </h3>
        </div>
        <ul>
          <li>
            <a href="index.php" class="sidebar-link">
              <i class="fa-solid fa-house fa-fw"></i>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="./pages/Profile.php" class="sidebar-link">
              <i class="fa-solid fa-user fa-fw"></i><span>Profile</span>
            </a>
          </li>
          <li>
            <a href="./pages/events.php" class="sidebar-link">
              <i class="fa-solid fa-calendar-day fa-fw"></i><span>Events</span>
            </a>
          </li>
          <li>
            <a href="./pages/eventstatus.php" class="sidebar-link">
              <i class="fa-solid fa-calendar-check fa-fw"></i
              ><span>Event Status</span>
            </a>
          </li>
          <li>
            <a href="./pages/Payment Status.php" class="sidebar-link">
              <i class="fa-solid fa-credit-card fa-fw"></i
              ><span>Payment Status</span>
            </a>
          </li>
          <li>
            <a href="./pages/pricing.php" class="sidebar-link">
              <i class="fa-solid fa-tag fa-fw"></i><span>Pricing</span>
            </a>
          </li>
          <li>
            <a href="./pages/feedback.php" class="sidebar-link">
              <i class="fa-solid fa-comment-dots fa-fw"></i
              ><span>Feedback</span>
            </a>
          </li>
          <li>
            <a href="./pages/settings.php" class="sidebar-link">
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
<br>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="No Image" style="border-radius: 50%;" />

          </div>
        </div>
        <div class="main-content">
          <div class="title">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
          </div>
          <div class="main-content-boxes">
          <div class="box first-box">
             <div class="box-section1">
            <div class="box-title">
                <h2><br></h2>
                <p><br></p>
            </div>
           
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="avatar" style="width: 100px; height: 100px; object-fit: cover;" />
           

        </div>
        <div class="box-section2" style="margin-top: 20px;">
            <ul style="list-style-type: none; padding: 0;">
                <li>
                    <span><?php echo htmlspecialchars($user['username']); ?></span>
                    <p>Contact: <?php echo htmlspecialchars($user['contact_details']); ?></p>
                </li>
                <li>
                    <span><?php echo htmlspecialchars($user['aadhar_number']); ?></span>
                    <p>Aadhar Card</p>
                </li>
                <li>
                    <span><?php echo htmlspecialchars($user['pan_number']); ?></span>
                    <p>PAN Card</p>
                </li>
               
            </ul>
        </div>
        <a href="./pages/Profile.php" style="display: block; text-align: center; margin-top: 20px;">Profile</a>
        <div class="box">
    <div class="box-section1">
        <form action="./Database/upload_image.php" method="post" enctype="multipart/form-data" style="display: flex; align-items: center; gap: 10px; margin-top: 10px; padding: 10px; border-radius: 5px;">
            <input type="file" name="profile_picture" accept="image/*" style="padding: 5px; border-radius: 5px; outline: none;" />
            <input type="submit" value="Change Profile Picture" style="background-color: #0075ff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;" />
        </form>
    </div>
</div>

</div><div class="box">
  <div class="box-section1">
    <div class="box-title">
      <h2>Social Media Stats</h2>
    </div>
  </div>
  <div class="social-media-stats">
    <div class="media">
      <div class="media-icon twitter">
        <i class="fa-brands fa-twitter fa-2x"></i>
      </div>
      <ul>
        <li class="twitter">
          <?php if (!empty($user['twitter_username'])): ?>
            <a href="https://twitter.com/<?php echo htmlspecialchars($user['twitter_username']); ?>" target="_blank" rel="noopener noreferrer">
              <?php echo htmlspecialchars($user['twitter_username']); ?>
            </a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </li>
      </ul>
    </div>

    <div class="media">
      <div class="media-icon facebook">
        <i class="fa-brands fa-facebook-f fa-2x"></i>
      </div>
      <ul>
        <li class="facebook">
          <?php if (!empty($user['facebook_username'])): ?>
            <a href="https://facebook.com/<?php echo htmlspecialchars($user['facebook_username']); ?>" target="_blank" rel="noopener noreferrer">
              <?php echo htmlspecialchars($user['facebook_username']); ?>
            </a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </li>
      </ul>
    </div>

    <div class="media">
      <div class="media-icon youtube">
        <i class="fa-brands fa-youtube fa-2x"></i>
      </div>
      <ul>
        <li class="youtube">
          <?php if (!empty($user['youtube_username'])): ?>
            <a href="https://youtube.com/user/<?php echo htmlspecialchars($user['youtube_username']); ?>" target="_blank" rel="noopener noreferrer">
              <?php echo htmlspecialchars($user['youtube_username']); ?>
            </a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </li>
      </ul>
    </div>

    <div class="media">
      <div class="media-icon linkedin">
        <i class="fa-brands fa-linkedin fa-2x"></i>
      </div>
      <ul>
        <li class="linkedin">
          <?php if (!empty($user['linkedin_username'])): ?>
            <a href="https://linkedin.com/in/<?php echo htmlspecialchars($user['linkedin_username']); ?>" target="_blank" rel="noopener noreferrer">
              <?php echo htmlspecialchars($user['linkedin_username']); ?>
            </a>
          <?php else: ?>
            Not Provided
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</div>

            </div>
            </div>
            <!-- <div class="box">
              <div class="box-section1">
                <div class="box-title">
                  <h2>Latest Post</h2>
                </div>
              </div>
              <div class="latest-post-section2">
                <div class="latest-post-person-info">
                  <img src="./images/avatar.png" alt="" />
                  <ul>
                    <li><h4>Zana Suleiman</h4></li>
                    <li><p>About 3 Hours Ago</p></li>
                  </ul>
                </div>
                <div class="post">
                  <p>
                    You Can Fool All Of The People Some Of The Time, And Some Of
                    The People All Of The Time, But You Can't Fool All Of The
                    People All Of The Time.
                  </p>
                </div>
                <div class="latest-post-likes-comments">
                  <ul>
                    <li>
                      <i class="fa-regular fa-heart heart"></i><span>1.8K</span>
                    </li>
                    <li>
                      <i class="fa-regular fa-comments"></i><span>500</span>
                    </li>
                  </ul>
                </div>
              </div> -->
</div>

           
        <div class="projects-box">
          <div class="box-section1">
            <div class="box-title">
              <h2>Projects</h2>
            </div>
          </div>
          <div class="projects-box-section2">
            <table>
              <thead>
                <tr>
                  <td>Name</td>
                  <td>Finish Date</td>
                  <td>Client</td>
                  <td>Price</td>
                  <td>Team</td>
                  <td>Status</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Ministry Wikipedia</td>
                  <td>10 May 2022</td>
                  <td>Ministry</td>
                  <td>$5300</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                    <img src="./images/team-03.png" alt="" />
                    <img src="./images/team-04.png" alt="" />
                  </td>
                  <td><a href="/#" class="pending">Pending</a></td>
                </tr>
                <tr>
                  <td>Zana Shop</td>
                  <td>12 Oct 2021</td>
                  <td>Zana Company</td>
                  <td>$1500</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                    <img src="./images/team-03.png" alt="" />
                  </td>
                  <td><a href="/#" class="in-progress">In Progress</a></td>
                </tr>
                <tr>
                  <td>Bouba App</td>
                  <td>05 Sep 2021</td>
                  <td>Bouba</td>
                  <td>$800</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                  </td>
                  <td><a href="/#" class="completed">Completed</a></td>
                </tr>
                <tr>
                  <td>Mahmoud Website</td>
                  <td>22 May 2021</td>
                  <td>Mahmoud</td>
                  <td>$600</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                  </td>
                  <td><a href="/#" class="completed">Completed</a></td>
                </tr>
                <tr>
                  <td>Sayed Website</td>
                  <td>24 May 2021</td>
                  <td>Sayed</td>
                  <td>300</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                  </td>
                  <td><a href="/#" class="rejected">Rejected</a></td>
                </tr>
                <tr>
                  <td>Arena Application</td>
                  <td>01 Mar 2021</td>
                  <td>Arena Company</td>
                  <td>$2600</td>
                  <td>
                    <img src="./images/team-01.png" alt="" />
                    <img src="./images/team-02.png" alt="" />
                    <img src="./images/team-03.png" alt="" />
                    <img src="./images/team-04.png" alt="" />
                  </td>
                  <td><a href="/#" class="completed">Completed</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    
    <script src="./js/script.js"></script>
    <script>
        function showAlert(message) {
            if (message) {
                alert(message); // Display the message using an alert
            }
        }
    </script>
  </body>
</html>
