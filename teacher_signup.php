<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marist Man Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <style>
   
   body {
    background: white;
    display: flex;
    flex-direction: column; /* This allows us to create a layout with a top header */
    height: 150vh; /* Full viewport height */

}
@media only screen and (max-width: 850px) {
    body {
    background: white;
    display: flex;
    flex-direction: column; /* This allows us to create a layout with a top header */
    height: 150vh; /* Full viewport height */
}

</style>
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
        <div class="wrapper" style="margin-bottom: 50px;">
            <form action="teacher_signup.php" method="POST">
                <div class="sign-in-heading">
                    <h2>Sign up as a Teacher</h2>
                </div>

                <h2 class="input-header-email">Courtesy</h2>
                <div class="input-box-email">
                    <input type="text" name="courtesy" placeholder="Courtesy" required style="width: 100%;">
                </div>
                <h2 class="lato-light-italic">(e.g Mrs, Mr, etc)</h2>

                <h2 class="input-header-email" style="margin-top: 10px;">First Name</h2>
                <div class="input-box-email">
                    <input type="text" name="first_name" placeholder="First Name" required style="width: 100%;">
                </div>

                <h2 class="input-header-email">Last Name</h2>
                <div class="input-box-email">
                    <input type="text" name="last_name" placeholder="Last Name" required style="width: 100%;">
                </div>

                <h2 class="input-header-email">Email</h2>
                <div class="input-box-email">
                    <input type="email" name="email" placeholder="Email" required style="width: 100%;">
                </div>
                <h2 class="lato-light-italic">(Make sure it's your school email address)</h2>

                <h2 class="input-header-passsword" style="margin-top: 10px;">Password</h2>
                <div class="input-box-password-and-login">
                    <input type="password" name="password" placeholder="Password" required class="password-input">
                </div>

                <h2 class="input-header-email" style="margin-top: 10px;">Confirm Password</h2>
                <div class="input-box-email">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required style="width: 100%;">
                </div>

                <div class="input-box">
                    <input type="submit" value="Create account" class="btn" style="margin-top: 10px;">
                </div>

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

    <div class="footer">
        <div class="footer_line"></div>
        <h6>Preparing Young Men For Life</h6>
    </div>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $db_username = "root";
    $db_password = "firmi0Na!";
    $dbname = "user_auth";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $courtesy = $_POST['courtesy'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($courtesy) || empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Check if email already exists
    $check_email_sql = "SELECT id FROM teachers WHERE email = ?";
    $stmt_check_email = $conn->prepare($check_email_sql);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        die("Email already exists. Please use a different email.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into teachers table
    $insert_sql = "INSERT INTO teachers (first_name, last_name, email, courtesy, password) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("sssss", $first_name, $last_name, $email, $courtesy, $hashed_password);

    if ($stmt_insert->execute()) {
        header("Location: login.php"); // Redirect to login page after successful registration
        exit();
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }

    $stmt_insert->close();
    $conn->close();
}
?>

