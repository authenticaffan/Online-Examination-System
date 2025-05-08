<?php
session_start();
include("../includes/db.php");

// Check if cheating was detected
if (isset($_GET['cheated']) && $_GET['cheated'] == 1 && isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']); // Sanitize quiz ID

    if (isset($_SESSION['student_id'])) {
        $student_id = intval($_SESSION['student_id']); // Sanitize student ID

        // Check if a result already exists for this quiz and student
        $check = mysqli_query($conn, "SELECT * FROM results WHERE student_id='$student_id' AND quiz_id='$quiz_id'");
        if (mysqli_num_rows($check) > 0) {
            // Update the existing result to mark as cheated
            mysqli_query($conn, "UPDATE results SET cheated=1 WHERE student_id='$student_id' AND quiz_id='$quiz_id'");
        } else {
            // Insert a new result record with the cheated flag
            mysqli_query($conn, "INSERT INTO results (student_id, quiz_id, score, cheated) VALUES ('$student_id', '$quiz_id', 0, 1)");
        }
    }

    // Destroy the session
    session_unset();
    session_destroy();

    // Redirect to login page with a cheating message
    header("Location: login.php?msg=cheated");
    exit();
}

// Normal logout process
session_unset();
session_destroy();

// Redirect to login page with a logout message
header("Location: login.php?msg=logged_out");
exit();
?>