<?php

include 'db.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    mysqli_query($conn, "DELETE FROM attendance WHERE student_id = $id");
    mysqli_query($conn, "DELETE FROM students WHERE id = $id");

    header("Location: dashboard.php");
    exit();
}



?>