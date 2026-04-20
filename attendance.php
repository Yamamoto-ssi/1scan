<?php
include 'db.php';



$scan_result = '';
$student_info = '';
$attendance_marked = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $scanned_data = $_POST['scanned_data'] ?? '';
    
    if (!empty($scanned_data)) {
        preg_match('/ID: (\w+)/', $scanned_data, $matches);
        
        if (isset($matches[1])) {
            $studentid = $matches[1];
            
            $query = mysqli_query($conn, "SELECT * FROM students WHERE studentid='$studentid'");
            $student = mysqli_fetch_array($query);
            
            if ($student) {
                $student_info = $student;
                
                $today = date('Y-m-d');
                $time = date('H:i:s');
                
                $check_query = mysqli_query($conn, "SELECT * FROM attendance WHERE student_id={$student['id']} AND DATE(scan_time)='$today'");
                
                if ($check_query && mysqli_num_rows($check_query) == 0) {
            
                    $insert_query = "INSERT INTO attendance (student_id, student_name, studentid, section, scan_time, attendance) VALUES ('{$student['id']}', '{$student['name']}', '{$student['studentid']}', '{$student['section']}', '$today $time', 'present')";
                    
                    if (mysqli_query($conn, $insert_query)) {
                        $attendance_marked = true;
                        $scan_result = "✓ Attendance marked for " . $student['name'];
                    } else {
                        $scan_result = "Error marking attendance: " . mysqli_error($conn);
                    }
                } else {
                    $scan_result = "Already scanned today!";
                }
            } else {
                $scan_result = "Student not found!";
            }
        } else {
            $scan_result = "Invalid QR code format!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="attendance.css">
    <title>Barcode Scanner - 1Scan Attendance</title>
    
</head>
<body>
    <div class="container">
        <h1>Attendance Scanner</h1>
        
        <div class="scanner-container">
            <div class="instructions">
                <h3>Instructions:</h3>
                <ol>
                    <li>Click in the input field below</li>
                    <li>Scan the QR code with your barcode scanner</li>
                    <li>Attendance will be marked automatically (+1)</li>
                    <li>Students can only be scanned once per day</li>
                </ol>
            </div>
            
            <!-- Hidden input for barcode scanner (acts as keyboard input) -->
            <input type="text" id="barcode_input" class="scanner-input" placeholder="Focus here and scan QR code..." autofocus>
            
            <?php if ($attendance_marked): ?>
                <div class="result success">
                    Success <?= $scan_result ?>
                </div>
                <?php if (!empty($student_info)): ?>
                    <div class="student-info">
                        <h3>✓ Student Attendance Recorded:</h3>
                        <p><strong>Name:</strong> <?= $student_info['name'] ?></p>
                        <p><strong>ID:</strong> <?= $student_info['studentid'] ?></p>
                        <p><strong>Section:</strong> <?= $student_info['section'] ?></p>
                        <p><strong>Email:</strong> <?= $student_info['email'] ?></p>
                        <p><strong>Time:</strong> <?= date('h:i:s A') ?></p>
                    </div>
                    <div class="scan-count">Ready for next scan...</div>
                <?php endif; ?>
            <?php elseif (!empty($scan_result)): ?>
                <div class="result <?= strpos($scan_result, 'Already scanned today!') !== false ? 'warning' : 'error' ?>">
                    <?= $scan_result ?>
                </div>
                <div class="scan-count">Please scan again or contact admin</div>
            <?php endif; ?>
        </div>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="dashboard.php" style="color: #ffffff; text-decoration: none; font-weight: bold;">← Back to Dashboard</a> | 
            <a href="attendance_report.php" style="color: #ffffff; text-decoration: none; font-weight: bold;">View Attendance Report</a>
        </p>
    </div>
    
<!-- Here we used ai to use the barcode and for the scanning -->

    <script>
        const barcodeInput = document.getElementById('barcode_input');
        let barcodeBuffer = '';
        let barcodeTimeout;

        // Listen for barcode scanner input
        barcodeInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                // Barcode scanner sends Enter key at the end
                event.preventDefault();
                
                if (barcodeBuffer.trim()) {
                    submitBarcode(barcodeBuffer.trim());
                    barcodeBuffer = '';
                    barcodeInput.value = '';
                }
            } else {
                // Accumulate characters
                barcodeBuffer += event.key;
                barcodeInput.value = barcodeBuffer;
            }
        });

        // Handle paste event (barcode scanner might paste data)
        barcodeInput.addEventListener('paste', function(event) {
            event.preventDefault();
            const pastedText = (event.clipboardData || window.clipboardData).getData('text');
            
            if (pastedText.trim()) {
                submitBarcode(pastedText.trim());
                barcodeInput.value = '';
                barcodeBuffer = '';
            }
        });

        function submitBarcode(barcodeData) {
            // Send data via form
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'scanned_data';
            input.value = barcodeData;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        // Keep focus on input
        window.addEventListener('load', function() {
            barcodeInput.focus();
        });

        barcodeInput.addEventListener('blur', function() {
            // Auto-refocus after a short delay
            setTimeout(() => barcodeInput.focus(), 100);
        });
    </script>
</body>
</html>
