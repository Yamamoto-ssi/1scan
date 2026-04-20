<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>REGISTER STUDENT</h2>
            <form action="registerstudent.php" method="post">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="section" required>
                    <option value="" disabled selected>Select Section</option>
                    <option value="act1a">ACT1A</option>
                    <option value="act1b">ACT1B</option>
                    <option value="act1c">ACT1C</option>
                </select>
                <input type="text" name="studentid" placeholder="Student Id" required>
                <div class="btn-box">
                    <input type="submit" value="Update" class="btn">
                    <a href="dashboard.php">Cancel<a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>