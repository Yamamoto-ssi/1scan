<?php 
include 'db.php';
include 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$qrcode_generated = false;
$student_data = '';
$mail_sent = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $section = $_POST['section'];
    $studentid = $_POST['studentid'];
    $is_update = isset($_POST['is_update']) && $_POST['is_update'] == 1;
    $student_id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if ($is_update && $student_id) {

        $sql = "UPDATE students SET name='$name', email='$email', section='$section', studentid='$studentid' WHERE id=$student_id";
        
        if ($conn->query($sql) === TRUE) {
            $student_data = "<br> Name: $name <br> <br> Email: $email <br> <br> Section: $section <br> <br> ID: $studentid <br>";  
            $qrcode_generated = true;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM students WHERE email = ? OR studentid = ?");
        $stmt->bind_param('ss', $email, $studentid);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowCount = $result->fetch_assoc()['count'] ?? 0;
        $stmt->close();
        
        if ($rowCount > 0) {
            $error_message = 'This student email or ID is already registered. Please check your data before submitting again.';
        } else {

            $qrcode_dir = 'qrcodes';
            if (!is_dir($qrcode_dir)) {
                mkdir($qrcode_dir, 0777, true);
            }
            
            $student_data_qr = "Name: $name | Email: $email | Section: $section | ID: $studentid";
            $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($student_data_qr);
        

            $qrcode_filename = "qrcodes/student_" . $studentid . "_" . time() . ".png";
            $qr_image = file_get_contents($qr_url);
            file_put_contents($qrcode_filename, $qr_image);

            $sql = "INSERT INTO students (name, email, section, studentid, qrcode) VALUES ('$name', '$email', '$section', '$studentid', '$qrcode_filename')";

            if ($conn->query($sql) === TRUE) {
                $student_data = "<br> Name: $name <br> <br> Email: $email <br> <br> Section: $section <br> <br> ID: $studentid <br>";  
    
        $qrcode_generated = true;
        
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'brixsedillo@gmail.com';
            $mail->Password = 'snyr oqnf wjph yyat';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; 

            $mail->setFrom("brixsedillo@gmail.com", "1Scan");
            $mail->addAddress($email, $name);
            $mail->Subject = "Registration Successful !";
            $mail->isHTML(true);
            
            $mail->Body = "
            <h2>Welcome to 1Scan System!</h2>
            <p>Dear $name,</p>
            <p>Your registration has been successfully completed, Welcome!</p>
            <hr>
            <h3>Your Details:</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Section:</strong> $section</p>
            <p><strong>Student ID:</strong> $studentid</p>
            <hr>
            <img src='" . $qr_url . "' alt='Student QR Code' style='width: 200px; height: 200px; border: 1px solid #ddd; padding: 10px;'>
            <hr>
            <p>Thank you for registering with our 1Scan System.</p>
            <p>Best regards,<br>1Scan Team hehehehe</p>
            ";
            
            $mail->send();
            $mail_sent = true;
            
        } catch (Exception $e) {
            echo "<p style='color: orange; text-align: center;'> " . $mail->ErrorInfo . "</p>";
        }
        
        echo "<p style='color: black; text-align: center;'><b>New Student added successfully!</b></p>";
        if ($mail_sent) {
            echo "<p style='color: black; text-align: center;'></p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
    }
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
            <div style="text-align: center; margin-bottom: 30px; border: 2px solid black; padding: 20px;">
                <h3 style="color: blue;"><b>Student Registered Successfully!</b></h3>
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
                
                <p><a href="registerstudent.php" style="color: blue; text-decoration: none;"> <br> Register Another Student</a></p>
                <p><a href="index.php" style="color: blue; text-decoration: none;">Back to Login</a></p>
            </div>
        <?php else: ?>
     
        <div class="form-container">
            <h2>REGISTER STUDENT</h2>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; margin-bottom: 15px;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="registerstudent.php" method="post" id="register-form">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="section" required>
                    <option value="" disabled selected>Select Section</option>
                    <option value="act1a">ACT1A</option>
                    <option value="act1b">ACT1B</option>
                    <option value="act1c">ACT1C</option>
                    <option value="act1a">ACT1D</option>
                    <option value="act1b">ACT1E</option>
                    <option value="act1c">ACT1F</option>
                    <option value="act1a">ACT1G</option>
                    <option value="act1b">ACT1H</option>
                    <option value="act1c">ACT1I</option>
                </select>
                <input type="text" name="studentid" placeholder="Student Id" required>
                <input type="submit" id="register-button" value="Register">
            </form>
            <p style="margin-top: 15px;"><a href="index.php" style="color: blue; text-decoration: none;">Back to Login</a></p>
        </div>
        <?php endif; ?>
        <script>
            var registerForm = document.getElementById('register-form');
            if (registerForm) {
                registerForm.addEventListener('submit', function () {
                    var button = document.getElementById('register-button');
                    if (button) {
                        button.value = 'Submitting...';
                        button.disabled = true;
                    }
                });
            }
        </script>
    </div>
</body>
</html>