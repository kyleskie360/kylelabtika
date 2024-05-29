<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Sent Notice</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="alert alert-success mt-5" role="alert">
            <?php if (isset($_GET['message'])) {
                echo $_GET['message'];
            } else {
                echo "Verification email sent. Please check your email to verify your account.";
            } ?>
        </div>
        <a href="login.php" class="btn btn-primary">Go to Login</a>
    </div>
</body>
</html>
