<?php
session_start();

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "1scan_db"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data was sent
if (isset($_POST['username']) && isset($_POST['password'])) {
    $userInput = $_POST['username'];
    $passInput = $_POST['password'];

    // Using Prepared Statements for Security
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $userInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if ($passInput === $row['password']) {
            header("Location: dashboard.php"); // Redirect to a dashboard or home page after successful login
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: index.php");
            exit();
        }
    } else {    
        $_SESSION['error'] = "User not found.";
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}
$conn->close();
?>