<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sign Up</title>
    <link rel="stylesheet" href="style.css">
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
    <li class="back-arrow"><a href="sign_up_1.html"><img src="back_arrow.svg"></a></li>

    <!-- Content section to center the sign-in form -->
    <div class="content">
        <div class="wrapper" style="margin-bottom: 50px;">
            <form action="student_signup.php" method="POST">
                <!-- Centered Sign In heading -->
                <div class="sign-in-heading">
                    <h2>Sign up as a Student</h2>
                </div>
                <!-- First Name input -->
                <h2 class="input-header-email">First Name</h2>
                <div class="input-box-email">
                    <input type="text" name="first_name" placeholder="First Name" required style="width: 100%;">
                </div>
                <!-- Last Name input -->
                <h2 class="input-header-email">Last Name</h2>
                <div class="input-box-email">
                    <input type="text" name="last_name" placeholder="Last Name" required style="width: 100%;">
                </div>
                <!-- Email input -->
                <h2 class="input-header-email">Email</h2>
                <div class="input-box-email">
                    <input type="email" name="email" placeholder="Email" required style="width: 100%;">
                </div>
                <!-- Password input -->
                <h2 class="input-header-passsword" style="margin-top: 10px;">Password</h2>
                <div class="input-box-password-and-login">
                    <input type="password" name="password" placeholder="Password" required class="password-input">
                </div>
                <!-- Confirm Password input -->
                <h2 class="input-header-email" style="margin-top: 10px;">Confirm Password</h2>
                <div class="input-box-email">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required style="width: 100%;">
                </div>

                <!-- Submit button -->
                <div class="input-box">
                    <input type="submit" value="Create account" class="btn" style="margin-top: 10px;">
                </div>

                <!-- Separator with "or" between two lines -->
                <div class="separator">
                    <hr />
                    <span>or</span>
                    <hr />
                </div>

                <!-- Have an account? Button -->
                <div class="input-box">
                    <a href="login.php" class="button">
                        <button type="button" class="btn">Have an account?</button>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer section -->
    <div class="footer">
        <div class="footer_line"></div>
        <h6>Preparing Young Men For Life</h6>
    </div>
</body>
</html>



<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database configuration
    $servername = "localhost";
    $db_username = "root";  // Your database username
    $db_password = "firmi0Na!";  // Your database password
    $dbname = "user_auth";  // Your database name

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // Note: Will be hashed in the next step

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $sql = "INSERT INTO students (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute statement
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "User registered successfully.<br>";
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit(); // Ensure the script stops after redirection
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

