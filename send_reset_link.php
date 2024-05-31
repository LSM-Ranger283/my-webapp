<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    $email = trim($_POST["email"]);

    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Store the token in the database with an expiration time (e.g., 1 hour)
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $sql = "INSERT INTO password_reset (email, token, expiry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $token, $expiry);
    
    if ($stmt->execute()) {
        // Send an email with the reset link
        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;
        $to = $email;
        $subject = "Password Reset";
        $message = "You have requested a password reset. Click the link below to reset your password:\n\n";
        $message .= $reset_link;
        $headers = "From: your-email@example.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Password reset link sent to your email.";
        } else {
            echo "Failed to send password reset email.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
