<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <?php 
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red; text-align: center;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
    ?>
    <form action="login_process.php" method="POST">
                <input type="text" name="username" required>
                <input type="password" name="password" required>
        <button type="submit">Log in !</button>
    </form>
    <a href="registerstudent.php" class="reg">Register a Student</a> <br>
    <a href="registerteacher.php" class="reg">Register a Teacher</a>
    
</div>
</body>
</html>