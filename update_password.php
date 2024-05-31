<?php
// update_password.php

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

    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE students SET password = ? WHERE email IN (SELECT email FROM password_reset WHERE token = ? AND expiry > NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $token);

    if ($stmt->execute()) {
        // Delete the token from the password_reset table
        $sql_delete = "DELETE FROM password_reset WHERE token = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("
