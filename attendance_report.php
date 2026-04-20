<?php
include 'db.php';


$query = mysqli_query($conn, "SELECT * FROM attendance ORDER BY scan_time DESC LIMIT 100");

//hete we used ai to make an output for the date and time
$today = date('Y-m-d');
$today_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM attendance WHERE DATE(scan_time)='$today'");
$today_count = $today_query ? mysqli_fetch_array($today_query)['count'] : 0;

// Get total attendance this week
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM attendance WHERE DATE(scan_time) >= '$week_start'");
$week_count = $week_query ? mysqli_fetch_array($week_query)['count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance_report.css">
    <title>Attendance Report - 1Scan</title>
    <style>
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Attendance Report</h1>
        
        <div class="stats">
            <div class="stat-box stat-today">
                <h3>TODAY'S ATTENDANCE</h3>
                <div class="number"><?= $today_count ?></div>
            </div>
            <div class="stat-box stat-week">
                <h3>THIS WEEK'S ATTENDANCE</h3>
                <div class="number"><?= $week_count ?></div>
            </div>
        </div>
        
        <div class="controls">
            <a href="dashboard.php">← Back to Dashboard</a>
            <a href="attendance.php">Go to Scanner</a>
        </div>
        
        <h2>Recent Attendance Records</h2>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Student ID</th>
                    <th>Section</th>
                    <th>Scan Time</th>
                    <th>Status</th>
                </tr>
            </thead>
<!-- Here we used ai to make an output for the date and time -->
            <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_array($query)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['student_name'] ?></td>
                        <td><?= $row['studentid'] ?></td>
                        <td><?= $row['section'] ?></td>
                        <td><?= date('M d, Y h:i:s A', strtotime($row['scan_time'])) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($row['attendance']) ?>">
                                <?= strtoupper($row['attendance']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <?php if (mysqli_num_rows($query) == 0): ?>
            <p style="text-align: center; color: #666; margin-top: 20px;">No attendance records found.</p>
        <?php endif; ?>
    </div>
    

</body>
</html>
