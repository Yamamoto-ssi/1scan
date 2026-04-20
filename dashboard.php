<?php
    include 'db.php';
    $query = mysqli_query($conn, "SELECT * FROM students");
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <div style="margin-bottom: 20px;">
            <a href="attendance.php" style="padding: 10px 20px; background: #3B1C32; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                Scan a QR Code
            </a>
            <a href="attendance_report.php" style="padding: 10px 20px; background: #3B1C32; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-left: 10px;">
                View Attendance
            </a>
            <a href="index.php" style="padding: 10px 20px; background: #ff0000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-left: 10px;">
                Logout
            </a>
        </div>
        <table>
            <tr>
                <th>Student No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Section</th>
                <th>Student Id</th>
                <th>QRCode</th>
                <th>Action</th>
            </tr>
    <?php
    $no = 1;
        while($row = mysqli_fetch_array($query)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['section'] ?></td>
                <td><?= $row['studentid'] ?></td>
                <td>
                    <?php if (!empty($row['qrcode']) && file_exists($row['qrcode'])): ?>
                        <img src="<?= $row['qrcode'] ?>" alt="Student QR Code" style="width: 100px; height: 100px; border: 1px solid #ccc;">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id']?>" class="btn">Edit</a>
                    <a href="action.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
    <?php endwhile; ?>
        </table>
    </div>
</body>
</html>