<?php

$conn = mysqli_connect("localhost", "root", "", "1scan_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>