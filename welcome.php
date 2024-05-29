
<?php
session_start();

include_once 'db_connect.php';

$email = $_SESSION['email'];

$sql = "SELECT * FROM ipt102_db WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <h2 class="text-center">Welcome</h2>
                <p class="text-center">Here are your details:</p>
                <ul>
                    <li><strong>First Name:</strong> <?php echo $row['first_name']; ?></li>
                    <li><strong>Last Name:</strong> <?php echo $row['last_name']; ?></li>
                    <li><strong>Middle Name:</strong> <?php echo $row['middle_name']; ?></li>
                    <li><strong>Email:</strong> <?php echo $row['email']; ?></li>
                </ul>
                <div class="form-group">
                    <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
