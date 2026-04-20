<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-box">
    <div class="login-container">
    <h2>Login</h2>
    <?php 
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red; text-align: center;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
    ?>
    <form action="login_process.php" method="POST">
                <p class="text">Username:</p>
                <input type="text" name="username" required>
                <p class="text">Password:</p>
                <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>    
    <br>
    <br>
    <br>
    
    <a href="registerstudent.php" class="reg">Register a Student</a> <br>
    <a href="registerteacher.php" class="reg">Register a Teacher</a>
    </div>
    <div class="img-container">
        <img src="https://static01.nyt.com/images/2017/09/10/magazine/10alabama-1/10alabama-1-superJumbo-v3.jpg
" alt="">
    </div>
</div>
</body>
</html>