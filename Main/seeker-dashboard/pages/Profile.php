<?php
session_start();
include('../Database/config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../seeker/seekerlogin.html');
    exit();
}

// Function to handle and log errors
function handle_error($message) {
    echo "<script>alert('$message');</script>";
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];

// Fetch user details from 'wurkify_user' table
$sql_user = "SELECT username, email, profile_picture FROM `wurkify-user` WHERE id = ?";
if ($stmt_user = $conn->prepare($sql_user)) {
    $stmt_user->bind_param("i", $user_id);
    if (!$stmt_user->execute()) {
        handle_error('Error executing user query: ' . $stmt_user->error);
    }
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows === 1) {
        $user = $result_user->fetch_assoc();
        
        // Set default profile picture if not set
        $profile_picture = $user['profile_picture'] ? '../uploads/' . $user['profile_picture'] : '../default.jpeg';
        
        // Add profile picture to user data
        $user['profile_picture'] = $profile_picture;
    } else {
        handle_error('User not found');
    }
    $stmt_user->close();
} else {
    handle_error('Error preparing user details query: ' . $conn->error);
}

// Fetch social media details from 'user_social' table
$sql_social = "SELECT twitter_username, facebook_username, linkedin_username, youtube_username FROM user_social WHERE user_id = ?";
if ($stmt_social = $conn->prepare($sql_social)) {
    $stmt_social->bind_param("i", $user_id);
    if (!$stmt_social->execute()) {
        handle_error('Error executing social media query: ' . $stmt_social->error);
    }
    $result_social = $stmt_social->get_result();
    if ($result_social->num_rows === 1) {
        $social_media = $result_social->fetch_assoc();
    } else {
        $social_media = [
            'twitter_username' => 'N/A', 
            'facebook_username' => 'N/A', 
            'linkedin_username' => 'N/A', 
            'youtube_username' => 'N/A'
        ];
    }
    $stmt_social->close();
} else {
    handle_error('Error preparing social media query: ' . $conn->error);
}

// Fetch general information from 'user_general_info' table
$sql_general = "SELECT first_name, last_name, phone_number, dob, age, gender, country, state FROM user_general_info WHERE user_id = ?";
if ($stmt_general = $conn->prepare($sql_general)) {
    $stmt_general->bind_param("i", $user_id);
    if (!$stmt_general->execute()) {
        handle_error('Error executing general info query: ' . $stmt_general->error);
    }
    $result_general = $stmt_general->get_result();
    if ($result_general->num_rows === 1) {
        $general_info = $result_general->fetch_assoc();
    } else {
        $general_info = [
            'phone_number' => 'N/A', 
            'first_name' => 'N/A', 
            'last_name' => 'N/A', 
            'age' => 'N/A', 
            'dob' => 'N/A', 
            'gender' => 'N/A', 
            'country' => 'N/A', 
            'state' => 'N/A'
        ];
    }
    $stmt_general->close();
} else {
    handle_error('Error preparing general info query: ' . $conn->error);
}

// Fetch experience details from 'user_experience' table
$sql_experience = "SELECT job_title, company_name, location, start_date, end_date, description, skills, employment_type, achievements FROM user_experience WHERE user_id = ?";
if ($stmt_experience = $conn->prepare($sql_experience)) {
    $stmt_experience->bind_param("i", $user_id);
    if (!$stmt_experience->execute()) {
        handle_error('Error executing experience query: ' . $stmt_experience->error);
    }
    $result_experience = $stmt_experience->get_result();
    $experiences = [];
    while ($row = $result_experience->fetch_assoc()) {
        $experiences[] = $row;
    }
    $stmt_experience->close();
} else {
    handle_error('Error preparing experience query: ' . $conn->error);
}

// Fetch skills from 'skills' table
$sql_skills = "SELECT skill_name FROM skills WHERE user_id = ?";
if ($stmt_skills = $conn->prepare($sql_skills)) {
    $stmt_skills->bind_param("i", $user_id);
    if (!$stmt_skills->execute()) {
        handle_error('Error executing skills query: ' . $stmt_skills->error);
    }
    $result_skills = $stmt_skills->get_result();
    $skills = [];
    while ($row = $result_skills->fetch_assoc()) {
        $skills[] = $row;
    }
    $stmt_skills->close();
} else {
    handle_error('Error preparing skills query: ' . $conn->error);
}

// Fetch education details from 'user_education' table
$sql_education = "SELECT degree, institution, graduation_year FROM user_education WHERE user_id = ?";
if ($stmt_education = $conn->prepare($sql_education)) {
    $stmt_education->bind_param("i", $user_id);
    if (!$stmt_education->execute()) {
        handle_error('Error executing education query: ' . $stmt_education->error);
    }
    $result_education = $stmt_education->get_result();
    if ($result_education->num_rows > 0) {
        $education = $result_education->fetch_assoc();
    } else {
        $education = [
            'degree' => 'N/A', 
            'institution' => 'N/A', 
            'graduation_year' => 'N/A'
        ];
    }
    $stmt_education->close();
} else {
    handle_error('Error preparing education query: ' . $conn->error);
}

// Fetch body criteria from 'body_criteria' table
$sql_body_criteria = "SELECT height, weight FROM body_criteria WHERE user_id = ?";
if ($stmt_body_criteria = $conn->prepare($sql_body_criteria)) {
    $stmt_body_criteria->bind_param("i", $user_id);
    if (!$stmt_body_criteria->execute()) {
        handle_error('Error executing body criteria query: ' . $stmt_body_criteria->error);
    }
    $result_body_criteria = $stmt_body_criteria->get_result();
    if ($result_body_criteria->num_rows === 1) {
        $body_criteria = $result_body_criteria->fetch_assoc();
        $height = $body_criteria['height'];
        $weight = $body_criteria['weight'];
    } else {
        $height = 'N/A';
        $weight = 'N/A';
    }
    $stmt_body_criteria->close();
} else {
    handle_error('Error preparing body criteria query: ' . $conn->error);
}

// Fetch identification info from 'identification_info' table
$sql_identification = "SELECT aadhar, pan, address_line1, address_line2, city, state, zipcode FROM identification_info WHERE user_id = ?";
if ($stmt_identification = $conn->prepare($sql_identification)) {
    $stmt_identification->bind_param("i", $user_id);
    if (!$stmt_identification->execute()) {
        handle_error('Error executing identification info query: ' . $stmt_identification->error);
    }
    $result_identification = $stmt_identification->get_result();
    if ($result_identification->num_rows === 1) {
        $identification_info = $result_identification->fetch_assoc();
    } else {
        $identification_info = [
            'aadhar' => 'N/A', 
            'pan' => 'N/A', 
            'address_line1' => 'N/A', 
            'address_line2' => 'N/A', 
            'city' => 'N/A', 
            'state' => 'N/A', 
            'zipcode' => 'N/A'
        ];
    }
    $stmt_identification->close();
} else {
    handle_error('Error preparing identification info query: ' . $conn->error);
}

// Profile photo upload handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];
    $upload_dir = '../uploads/';
    $upload_file = $upload_dir . basename($file['name']);
    $file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($file_type, $allowed_types) && $file['size'] < 5000000) {
        if (move_uploaded_file($file['tmp_name'], $upload_file)) {
            // Update the database with new profile picture
            $sql_update = "UPDATE `wurkify-user` SET profile_picture = ? WHERE id = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param("si", $file['name'], $user_id);
                if (!$stmt_update->execute()) {
                    handle_error('Error updating profile picture: ' . $stmt_update->error);
                } else {
                    echo "<script>alert('Profile picture updated successfully');</script>";
                }
                $stmt_update->close();
            } else {
                handle_error('Error preparing update profile picture query: ' . $conn->error);
            }
        } else {
            handle_error('Error uploading file');
        }
    } else {
        handle_error('Invalid file type or size');
    }
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
    <title>Profile</title>
  </head>
  <body>
    <div class="page-content">
      <div class="sidebar">
        <div class="brand">
          <i class="fa-solid fa-xmark xmark"></i>
          <h3> <?php echo htmlspecialchars($user['username']); ?></h3>
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
            <h1>Profile</h1>
        </div>

        <div class="profile-box">
    <div class="profile-info" style="text-align: center; padding: 20px; background-color: #f9f9f9; border-radius: 10px; border: 1px solid #ddd;">
        <!-- Profile Picture with Pencil Icon -->
        <div style="position: relative; display: inline-block;">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
            <a href="#" onclick="document.getElementById('upload-photo-form').style.display='block'; return false;" style="position: absolute; bottom: 0; right: 0; background: #fff; border-radius: 50%; padding: 5px; border: 1px solid #ddd; color: #333; text-decoration: none;">
                <i class="fa-solid fa-pencil-alt fa-lg"></i>
            </a>
        </div>
        <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($user['username']); ?></h3>
        
        <!-- Social Media Links -->
        <div class="social-media-links" style="margin: 20px 0;">
            <a href="https://twitter.com/<?php echo htmlspecialchars($social_media['twitter_username']); ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 10px; color: #1DA1F2;">
                <i class="fa-brands fa-twitter fa-3x"></i>
            </a>
            <a href="https://facebook.com/<?php echo htmlspecialchars($social_media['facebook_username']); ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 10px; color: #1877F2;">
                <i class="fa-brands fa-facebook-f fa-3x"></i>
            </a>
            <a href="https://youtube.com/user/<?php echo htmlspecialchars($social_media['youtube_username']); ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 10px; color: #FF0000;">
                <i class="fa-brands fa-youtube fa-3x"></i>
            </a>
            <a href="https://linkedin.com/in/<?php echo htmlspecialchars($social_media['linkedin_username']); ?>" target="_blank" rel="noopener noreferrer" style="margin: 0 10px; color: #0A66C2;">
                <i class="fa-brands fa-linkedin fa-3x"></i>
            </a>
        </div>
        
        <!-- Upload Profile Photo Form -->
        <form action="../Database/upload_image.php" method="post" enctype="multipart/form-data" id="upload-photo-form" style="display: none; margin-top: 20px; padding: 15px; border-radius: 10px; background-color: #fff; border: 1px solid #ddd;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <input type="file" name="profile_picture" accept="image/*" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd; width: 80%;">
                <input type="submit" value="Change Profile Picture" style="background-color: #0075ff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            </div>
        </form>
        
        <a href="../logout.php" style="color: #0075ff; text-decoration: none; margin-top: 20px; display: inline-block;">LogOut</a>
    </div>



            <div class="profile-info-section2">
            <div class="row">
                    <div class="general-information">
                        <h4>General Information</h4>
                        <div>
                            <h5>Full Name:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($general_info['first_name']); ?> <?php echo htmlspecialchars($general_info['last_name']); ?></span>
                        </div>
                        
                        <div>
                            <h5>Date Of Birth:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($general_info['dob']); ?></span>
                        </div>
                    </div>
                    <div>
                        <h5>Gender:&nbsp;</h5>
                        <span><?php echo htmlspecialchars($general_info['gender']); ?></span>
                    </div>
                    <div>
                        <h5>Age:&nbsp;</h5>
                        <span><?php echo htmlspecialchars($general_info['age']); ?></span>
                    </div>
                    <div>
                        <h5>Country:&nbsp;</h5>
                        <span><?php echo htmlspecialchars($general_info['country']); ?></span>
                    </div>
                    <div>
                        <h5>State:&nbsp;</h5>
                        <span><?php echo htmlspecialchars($general_info['state']); ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="general-information">
                        <h4>Contact Information</h4>
                        <div>
                            <h5>Email:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div>
                            <h5>Phone:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($general_info['phone_number']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
    <div class="general-information">
        <h4>Identification Information</h4>
        <div>
            <h5>Aadhar:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['aadhar']); ?></span>
        </div>
        <div>
            <h5>PAN:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['pan']); ?></span>
        </div>
        <div>
            <h5>Address Line 1:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['address_line1']); ?></span>
        </div>
        <div>
            <h5>Address Line 2:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['address_line2']); ?></span>
        </div>
        <div>
            <h5>City:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['city']); ?></span>
        </div>
        <div>
            <h5>State:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['state']); ?></span>
        </div>
        <div>
            <h5>Zipcode:&nbsp;</h5>
            <span><?php echo htmlspecialchars($identification_info['zipcode']); ?></span>
        </div>
    </div>
</div>
                <div class="row">
                    <div class="general-information">
                        <h4>Education Information</h4>
                        <div>
                            <h5>Degree:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($education['degree']); ?></span>
                        </div>
                        <div>
                            <h5>Institute:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($education['institution']); ?></span>
                        </div>
                        <div>
                            <h5>Graduation Year:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($education['graduation_year']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="general-information">
                        <h4>Body Metrics</h4>
                        <div>
                            <h5>Height:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($height); ?> cm</span>
                        </div>
                        <div>
                            <h5>Weight:&nbsp;</h5>
                            <span><?php echo htmlspecialchars($weight); ?> kg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content-boxes profile-main-content-boxes">
            <div class="box">
                <div class="box-section1">
                    <div class="box-title">
                        <h2>My Skills</h2>
                        <p>Complete Skills List</p>
                    </div>
                </div>
                <div class="profile-skills" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
      
        <div class="row" style="display: flex; flex-wrap: wrap; gap: 15px;">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 15px; width: 100%;">
                <?php
                if (!empty($skills)) {
                    foreach ($skills as $skill) {
                        echo '<li style="flex: 1 1 calc(50% - 15px); box-sizing: border-box; border: 1px solid #ddd; border-radius: 8px; padding: 10px; background-color: #f9f9f9; color: #333;">' . htmlspecialchars($skill['skill_name']) . '</li>';
                    }
                } else {
                    echo '<li style="flex: 1 1 calc(50% - 15px); box-sizing: border-box; border: 1px solid #ddd; border-radius: 8px; padding: 10px; background-color: #f9f9f9; color: #333;">No skills found</li>';
                }
                ?>
            </ul>
        </div>
    </div>
            </div>

            <div class="box latest-activities">
                <div class="box-section1">
                    <div class="box-title">
                        <h2>Latest Experiences</h2>
                        <p>Recent Experiences Added by the User</p>
                    </div>
                </div>
                <div class="profile-latest-activities">
                    <?php foreach ($experiences as $experience): ?>
                        <div class="profile-latest-activities-row">
                            <div class="row-info">
                                <img src="../images/experience.avif" alt="" />
                                <div>
                                    <span><?php echo htmlspecialchars($experience['employment_type']); ?></span>
                                    <h4><?php echo htmlspecialchars($experience['job_title']); ?> at <?php echo htmlspecialchars($experience['company_name']); ?></h4>
                                </div>
                            </div>
                            <div class="row-history">
                                <h4><?php echo htmlspecialchars(date('j F Y', strtotime($experience['start_date']))); ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

    </div>
    <script src="../js/script.js"></script>
  </body>
</html>
