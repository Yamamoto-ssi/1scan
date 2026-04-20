<?php 
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $studentid = $_POST['studentid'];

    $sql = "INSERT INTO students (name, email, studentid) VALUES ('$name', '$email', '$studentid')";

    if ($conn->query($sql) === TRUE) {
        echo "New Student added successfully";
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
        <h2>REGISTER STUDENT</h2>
        <form action="registerstudent.php" method="post">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="studentid" placeholder="Student Id" required>
            <input type="submit" value="Register">
        </form> 
    </div>
</body>
</html>