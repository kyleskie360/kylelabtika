<?php 
session_start();
include "db_connect.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$errors = array(); // Initialize errors array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : ''; // Make middle name optional
    $email = $_POST['email'];
    $status = 'Pending';
    $active = 0;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format!";
    } elseif (!preg_match('/^[a-zA-Z]+$/', $username)) {
        $errors['username'] = "Username should only contain letters!";
    } elseif ($password !== $conf_password) {
        $errors['password'] = "Passwords do not match!";
    } else {
        // Check if email already exists in the users_verification table
        $email_check_sql = "SELECT * FROM users_verification WHERE email = '$email'";
        $email_check_result = mysqli_query($conn, $email_check_sql);
        if (mysqli_num_rows($email_check_result) > 0) {
            $errors['email'] = "Email address already exists. Please choose a different email.";
        } else {
            $verification_code = rand(100000, 999999);
            $sql = "INSERT INTO users_verification (email, verification_code, verification_status) VALUES ('$email', '$verification_code', '$status')";
            $result = mysqli_query($conn, $sql);
            
            if ($result) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO ipt102_db (username, password, first_name, last_name, middle_name, email, status, active) 
                        VALUES ('$username', '$hashed_password', '$firstName', '$lastName', '$middleName', '$email', '$status', '$active')";
                $result = mysqli_query($conn, $sql);
                
                if ($result) {
                    // Send verification email
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kyleskie360@gmail.com';
                    $mail->Password = 'zghpsbrtpzazicvm';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    
                    $mail->setFrom('kyleskie360@gmail.com');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification';
                    $mail->Body = 'Please click the "verify" link to verify your email: <a href="http://localhost/loginregister/verify.php?email='.$email.'&code='.$verification_code.'">Verify</a>';
                    
                    try {
                        $mail->send();
                        header("Location: sent_notice.php?message=Verification email sent. Please check your email to verify your account.");
                        exit();
                    } catch (Exception $e) {
                        $errors['email'] = "Failed to send verification email. Please try again later.";
                    }
                } else {
                    $errors['db-error'] = "Error occurred while inserting data into database!";
                }
            } else {
                $errors['db-error'] = "Error occurred while storing verification code.";
            }
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="register.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Signup Form</h2>
                    <p class="text-center">IPT102</p>
                    <?php
                    if (!empty($errors)) {
                        // Display validation errors
                        echo '<div class="alert alert-danger">';
                        foreach ($errors as $error) {
                            echo "<p>$error</p>";
                        }
                        echo '</div>';
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="firstName" placeholder="First Name" required value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="lastName" placeholder="Last Name" required value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="middleName" placeholder="Middle Name" value="<?php echo isset($_POST['middleName']) ? $_POST['middleName'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="conf_password" placeholder="Confirm password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="signup" value="Signup">
                    </div>
                    <div class="link login-link text-center">Already a member? <a href="login.php">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
