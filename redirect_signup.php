<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup_type"])) {
    $signup_type = $_POST["signup_type"];
    if ($signup_type === "teacher") {
        header("Location: teacher_sign_up.php");
        exit;
    } elseif ($signup_type === "student") {
        header("Location: student_sign_up.php");
        exit;
    }
} else {
    // Redirect to a default page or display an error message if signup_type is not set
    header("Location: sign_up1.html"); // Redirect back to the selection page if there's an error
    exit;
}
?>
