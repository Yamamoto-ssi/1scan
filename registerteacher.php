<?php 
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New teacher added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
</head>
<body>

    <div class="container">
        <h2>REGISTER TEACHER</h2>
        <form action="registerteacher.php" method="post" class="form-container">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form> 
    </div>
</body>
</html>