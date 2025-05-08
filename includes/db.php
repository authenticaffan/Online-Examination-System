<?php
date_default_timezone_set('Asia/Kolkata');
// Use object-oriented style for better readability and consistency
$conn = new mysqli("localhost", "u350630892_mcqcahcet", "Mcq@cahcet123", "u350630892_mcq");

// Check the connection
if ($conn->connect_error) {
    // Error handling - more detailed message and exit
    die("Connection failed: " . $conn->connect_error);
}
?>
