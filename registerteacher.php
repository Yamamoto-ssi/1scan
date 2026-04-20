<?php 
include 'db.php';
include 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$registration_success = false;
$mail_sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $registration_success = true;
        
  
        $teacher_data = "Username: $username | Email: $email";
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($teacher_data);

        $mail = new PHPMailer(true);
        
        try {

            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = MAIL_PORT;

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($email, $username);
            $mail->Subject = "Teacher Registration Successful - 1Scan System";
            $mail->isHTML(true);
            

            $mail->Body = "
            <h2>Welcome to 1Scan System!</h2>
            <p>Dear $username,</p>
            <p>Your teacher registration has been successfully completed!</p>
            <hr>
            <h3>Your Login Details:</h3>
            <p><strong>Username:</strong> $username</p>
            <p><strong>Email:</strong> $email</p>
            <hr>
            <h3>Your QR Code:</h3>
            <p>Scan this QR code to save your login information:</p>
            <img src='" . $qr_url . "' alt='Teacher QR Code' style='width: 200px; height: 200px; border: 1px solid #ddd; padding: 10px;'>
            <hr>
            <p>You can now login to the system with your credentials.</p>
            <p>Best regards,<br>1Scan Team</p>
            ";
            
            $mail->send();
            $mail_sent = true;
            
        } catch (Exception $e) {

            echo "<p style='color: orange;'>✓ Teacher added but email failed: " . $mail->ErrorInfo . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

    <div class="container">
        <div class="form-container">
            <?php if ($registration_success): ?>
                <h2 style="color: green;">✓ Teacher Registration Successful!</h2>
                <p style="color: green;">Welcome to 1Scan System!</p>
                <?php if ($mail_sent): ?>
                    <p style="color: green;">✓ A confirmation email has been sent.</p>
                <?php endif; ?>
                <p><a href="index.php" style="color: blue; text-decoration: none;">Back to Login</a></p>
            <?php else: ?>
                <h2>REGISTER TEACHER</h2>
                <form action="registerteacher.php" method="post">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" value="Register">
                </form>
                <p style="margin-top: 15px;"><a href="index.php" style="color: blue; text-decoration: none;">Back to Login</a></p>
            <?php endif; ?>
        </div>
    </div>
        </div>
    </div>
</body>
</html>