<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="title-logo">
            <img src="Marist Man Logo (1).png" alt="Marist_Man_Logo" class="logo">
            <h1>St John's College</h1>
        </div>
        <div class="header_line"></div>
    </div>
    <div class="content">
        <div class="wrapper">
            <form action="login.php" method="post">
                <div class="sign-in-heading">
                    <h2>Sign In</h2>
                </div>
                
                <div class="input-box-email"> </div>
                    <h2 class="input-header-email">Email</h2>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box-password">
                    <h2 class="input-header-password">Password</h2>
                    <input type="password" name="password" placeholder="Password" required class="password-input">
                </div>

                <input type="submit" value="Login" class="login-button">Login</button>
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="forgot_password.php">Forgot password?</a>
                </div>
                <div class="separator">
                    <hr />
                    <span>or</span>
                    <hr />
                </div>
                <div class="input-box">
                    <a href="sign_up_1.html" class="button">
                        <button type="button" class="btn">Create a new account</button>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <div class="footer_line"></div>
        <h6>Preparing Young Men For Life</h6>
    </div>
</body>
</html>




<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Debugging: Check if email and password are provided
    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        die("Please enter email and password.");
    }

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Prepare SQL statement to check if the user exists
    $sql = "SELECT id, first_name, last_name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $first_name, $last_name, $db_email, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password verified
                // Store data in session variables
                $_SESSION["id"] = $id;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["email"] = $db_email;

                // Determine user type and redirect accordingly
                // For demonstration, let's assume student if email ends with "@student"
                if (strpos($db_email, "@student") !== false) {
                    // Student login
                    header("Location: welcome_student.html");
                    exit;
                } else {
                    // Teacher or other user type login
                    header("Location: welcome_teacher.html");
                    exit;
                }
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that email.";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>
