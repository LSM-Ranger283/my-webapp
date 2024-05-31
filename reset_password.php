<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="path/to/style.css">
</head>
<body>
    <h2>Reset Password</h2>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
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
    $token = $_POST["token"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the token exists and is not expired
    $sql = "SELECT email FROM password_reset WHERE token = ? AND expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email);
        $stmt->fetch();

        // Update the password in the students table
        $sql_update = "UPDATE students SET password = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $password, $email);
        if ($stmt_update->execute()) {
            echo "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
            
            // Delete the token from the password_reset table
            $sql_delete = "DELETE FROM password_reset WHERE token = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("s", $token);
            $stmt_delete->execute();
            $stmt_delete->close();
        } else {
            echo "Error updating password: " . $conn->error;
        }
        
        $stmt_update->close();
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
}

$conn->close();
?>

