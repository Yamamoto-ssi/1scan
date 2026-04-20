<?php
// Email Configuration for PHPMailer
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'your-email@gmail.com');  // Your Gmail address
define('MAIL_PASS', 'your-app-password');      // Gmail App Password (not your regular password)
define('MAIL_FROM', 'your-email@gmail.com');
define('MAIL_FROM_NAME', '1Scan System');
define('MAIL_PORT', 587);

// To get Gmail App Password:
// 1. Go to https://myaccount.google.com/security
// 2. Enable 2-Factor Authentication
// 3. Go to App passwords and generate a password for "Mail"
// 4. Copy the 16-character password and paste it above
?>
