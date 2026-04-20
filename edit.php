<?php
    include 'db.php';
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
    $row = mysqli_fetch_array($query);

?>    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>UPDATE STUDENT LIST</h2>
            <form action="registerstudent.php" method="post">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="is_update" value="1">
                <input type="text" name="name" placeholder="Name" value="<?= $row['name'] ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?= $row['email'] ?>" required>
                <select name="section" value="<?= $row['section'] ?>" required>
                    <option value="" disabled selected>Select Section</option>
                    <option value="act1a" <?= $row['section'] == 'act1a' ? 'selected' : '' ?>>ACT1A</option>
                    <option value="act1b" <?= $row['section'] == 'act1b' ? 'selected' : '' ?>>ACT1B</option>
                    <option value="act1c" <?= $row['section'] == 'act1c' ? 'selected' : '' ?>>ACT1C</option>
                </select>
                <input type="text" name="studentid" placeholder="Student Id" value="<?= $row['studentid'] ?>" required>
                <div class="btn-box">
                    <button type="submit" value="Update" class="btn">Update</button>
                    <a href="dashboard.php">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>