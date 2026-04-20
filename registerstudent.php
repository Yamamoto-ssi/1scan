<?php 
include 'db.php';
include 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$qrcode_generated = false;
$student_data = '';
$mail_sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $section = $_POST['section'];
    $studentid = $_POST['studentid'];

    $sql = "INSERT INTO students (name, email, section, studentid) VALUES ('$name', '$email', '$section', '$studentid')";

    if ($conn->query($sql) === TRUE) {
        $student_data = "Name: $name | Email: $email | Section: $section | ID: $studentid";
    
        $qrcode_generated = true;
        
        // Generate QR code URL
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($student_data);
        
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'brixsedillo@gmail.com';
            $mail->Password = 'snyr oqnf wjph yyat';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; 

            $mail->setFrom("brixsedillo@gmail.com", "1Scan System");
            $mail->addAddress($email, $name);
            $mail->Subject = "Registration Successful - 1Scan System";
            $mail->isHTML(true);
            
            $mail->Body = "
            <h2>Welcome to 1Scan System!</h2>
            <p>Dear $name,</p>
            <p>Your registration has been successfully completed!</p>
            <hr>
            <h3>Your Details:</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Section:</strong> $section</p>
            <p><strong>Student ID:</strong> $studentid</p>
            <hr>
            <img src='" . $qr_url . "' alt='Student QR Code' style='width: 200px; height: 200px; border: 1px solid #ddd; padding: 10px;'>
            <hr>
            <p>Thank you for registering with 1Scan System.</p>
            <p>Best regards,<br>1Scan Team</p>
            ";
            
            $mail->send();
            $mail_sent = true;
            
        } catch (Exception $e) {
            echo "<p style='color: orange; text-align: center;'>✓ Student added but email failed: " . $mail->ErrorInfo . "</p>";
        }
        
        echo "<p style='color: green; text-align: center;'><b>New Student added successfully!</b></p>";
        if ($mail_sent) {
            echo "<p style='color: green; text-align: center;'>✓ Confirmation email sent!</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register!</title>
    <link rel="stylesheet" href="register.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="container">
        <?php if ($qrcode_generated): ?>
            <div style="text-align: center; margin-bottom: 30px; border: 2px solid green; padding: 20px;">
                <h3 style="color: blue;">✓ Student Registered Successfully!</h3>
                <p><strong>Student Data:</strong> <?php echo $student_data; ?></p>
                <h3>QR Code:</h3>
                <div id="qrcode" style="display: inline-block; padding: 10px; border: 1px solid #ccc;"></div>
                <script>
                    new QRCode(document.getElementById("qrcode"), {
                        text: "<?php echo $student_data; ?>",
                        width: 200,
                        height: 200,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                </script>
                <p><a href="registerstudent.php" style="color: blue; text-decoration: none;">Register Another Student</a></p>
            </div>
        <?php else: ?>
     
        <div class="form-container">
            <h2>REGISTER STUDENT</h2>
            <form action="registerstudent.php" method="post">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="section" required>
                    <option value="" disabled selected>Select Section</option>
                    <option value="act1a">ACT1A</option>
                    <option value="act1b">ACT1B</option>
                    <option value="act1c">ACT1C</option>
                </select>
                <input type="text" name="studentid" placeholder="Student Id" required>
                <input type="submit" value="Register">
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>