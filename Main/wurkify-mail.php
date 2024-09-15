<?php
// Enable all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the configuration file
include('./Database/config.php');

// Important PHPMailer files
require "./vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "./vendor/phpmailer/phpmailer/src/SMTP.php";
require "./vendor/phpmailer/phpmailer/src/Exception.php";
require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send the success email
function sendMail($send_to, $name) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use proper encryption
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;

        // Use environment variables or secure methods for credentials
        $mail->Username = "wurkify01@gmail.com";
        $mail->Password = "yfas iosf fbbu lehi"; // Avoid hardcoding sensitive information

        // Sender details
        $mail->setFrom("wurkify01@gmail.com", "Wurkify");

        // Recipient
        $mail->addAddress($send_to);

        // Subject and Body
        $mail->Subject = "Welcome to Wurkify!";
        $mail->isHTML(true); // Enable HTML format
        $mail->Body = "
            <html>
            <body>
                <p>Dear <strong>{$name}</strong>,</p>
                
                <p>Congratulations and welcome to <strong>Wurkify!</strong></p>
                
                <p>We are thrilled to have you as a part of our community. Your account has been successfully created. To get started, you can log in to your account using your credentials.</p>

                <p>If you have any questions or need assistance, please feel free to reach out to our support team.</p>

                <p>Thank you for choosing <strong>Wurkify</strong>. We look forward to helping you achieve your goals!</p>

                <br>
                <p>Best regards,</p>
                <p><strong>The Wurkify Team</strong></p>

                <p><small>P.S. If you did not create this account, please contact us immediately.</small></p>
            </body>
            </html>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));
    $role = htmlspecialchars(trim($_POST['role'])); // Get the role value

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.location.href='./wurkify-register.html';</script>";
        exit();
    }

    // Validate password strength
    $passwordStrengthRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordStrengthRegex, $password)) {
        echo "<script>alert('Password must be at least 8 characters long, contain one uppercase letter, one number, and one special character.'); window.location.href='./wurkify-register.html';</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists
    $checkSql = "SELECT * FROM `wurkify-user` WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already exists.'); window.location.href='./wurkify-register.html';</script>";
        $checkStmt->close();
        $conn->close();
        exit();
    }

    // Prepare and execute SQL query
    $sql = "INSERT INTO `wurkify-user` (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        // Send the success email
        sendMail($email, $username);

        // Redirect to a success page
        echo "<script>alert('Registration successful! An email has been sent to you.'); window.location.href='./wurkify-login.html';</script>";
    } else {
        echo "<script>alert('Error: Could not register.'); window.location.href='./wurkify-register.html';</script>";
    }

    $stmt->close();
    $checkStmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
