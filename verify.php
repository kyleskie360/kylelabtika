<?php
session_start();
include "db_connect.php";

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
if(isset($_GET['email']) && isset($_GET['code'])) {
$email = $_GET['email'];
$verification_code = $_GET['code'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users_verification WHERE email = ? AND verification_code = ?");
$stmt->bind_param("ss", $email, $verification_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows == 1) {
// Verification successful, update user status
$update_stmt = $conn->prepare("UPDATE ipt102_db SET status = 'Active', active = 1 WHERE email = ?");
$update_stmt->bind_param("s", $email); // Corrected bind_param call
if ($update_stmt->execute()) {
// Remove verification entry from users_verification table
$delete_stmt = $conn->prepare("DELETE FROM users_verification WHERE email = ?");
$delete_stmt->bind_param("s", $email); // Corrected bind_param call
$delete_stmt->execute();
$message = "Email verification successful. You can now login.";
} else {
$error_message = "Failed to update user status.";
}
} else {
$error_message = "Invalid verification link.";
}
} else {
$error_message = "Invalid verification link.";
}
} else {
$error_message = "Invalid request method.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Email Verification</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<?php if (!empty($error_message)) { ?>
<div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php } elseif (!empty($message)) { ?>
<div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<div class="mt-3 text-center">
<p>Return to <a href="login.php">Login</a></p>
</div>
</div>
</body>
</html>