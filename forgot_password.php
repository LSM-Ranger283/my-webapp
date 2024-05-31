<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="path/to/style.css">
    </head>
    <body>
        <!-- Header section -->
        <div class="header">
            <div class="title-logo">
                <img src="Marist Man Logo (1).png" alt="Marist_Man_Logo" class="logo">
                <h1>St John's College</h1>
            </div>
            <div class="header_line"></div>
        </div>
        <li class="back-arrow"><a href="login.html"><img src="back_arrow.svg"></a></li>
        <!-- Content section to center the sign-in form -->
        <div class="content">
            <div class="wrapper">
                <form action="forgot_password.php" method="post">
                    <!-- Centered Sign In heading -->
                    <div class="sign-in-heading">
                        <h2>Forgot password</h2>
                    </div>
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" required><br><br>
                    <input type="submit" value="Reset Password">
                    <!--<button type="submit" class="login-button" style="margin-left: 80px;">Student</button>-->
                    <h2 class="input-header-email">Email</h2>
                    <!-- Username and password fields -->
                    <div class="input-box-email">
                        <input type="text" placeholder="Email" required> </div>
                        <!-- Remember and forgot password links -->
                        <a href="" class="button">
                            <input type="submit" class="btn" style="margin-top: 10px;" value="Reset Password" >
                        </a>
                        <!-- Separator with "or" between two lines -->
                        <div class="separator">
                            <hr />
                            <span>or</span>
                            <hr />
                        </div>
                        <!-- Have an account button -->
                        <a href="login.html" class="button">
                            <center><button type="button" class="btn" style="margin-top: 20px; width: 50%;" >Have an account? </button></center>
                        </a>
                    </form>
                </div>
            </div>
        </body>
    <div class="footer">
         <div class="footer_line"> </div>
         <h6>Preparing Young Men For Life</h6>
        </div>
    </div>
</html>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$db_username = "root";
$db_password = "firmi0Na!"; // Your MySQL root password
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Check if the email exists in the students table
    $sql = "SELECT id, email FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Set token expiry to 1 hour from now
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert the token into the password_reset table
        $sql_insert = "INSERT INTO password_reset (email, token, expiry) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $email, $token, $expiry);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Send email with the reset link (example here, replace with your email sending code)
        $reset_link = "http://localhost/reset_password.php?token=" . $token;
        echo "Password reset link sent to your email. Click <a href='$reset_link'>here</a> to reset your password.";
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
