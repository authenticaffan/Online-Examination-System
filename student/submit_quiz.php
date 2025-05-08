<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student_id = intval($_SESSION['student_id']); // Sanitize student ID
$quiz_id = intval($_POST['quiz_id']); // Sanitize quiz ID
$score = 0;

// Get quiz questions
$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id='$quiz_id'");

while ($question = mysqli_fetch_assoc($questions)) {
    $question_id = $question['id'];
    $selected_option = isset($_POST['q' . $question_id]) ? $_POST['q' . $question_id] : null;

    if ($selected_option == $question['correct_option']) {
        $score++;
    }

    // Save student answer
    $stmt = $conn->prepare("INSERT INTO student_answers (student_id, quiz_id, question_id, selected_option) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $student_id, $quiz_id, $question_id, $selected_option);
    $stmt->execute();
}

// Save result
$stmt = $conn->prepare("INSERT INTO results (student_id, quiz_id, score) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $student_id, $quiz_id, $score);
$stmt->execute();

// Get result publish date
$q = mysqli_query($conn, "SELECT result_publish_date FROM quizzes WHERE id = '$quiz_id'");
$data = mysqli_fetch_assoc($q);
$result_date = $data['result_publish_date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Submitted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-hover: #388E3C;
            --background: #f9fafb;
            --card-bg: #ffffff;
            --text: #1f2937;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .message-box {
            background-color: var(--card-bg);
            border: 2px solid var(--primary);
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 10px 30px var(--shadow);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        p {
            font-size: 1rem;
            color: var(--text);
            margin: 10px 0;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s, transform 0.2s;
        }

        .back-link:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .message-box {
                padding: 20px;
                width: 90%;
            }

            h2 {
                font-size: 1.5rem;
            }

            p {
                font-size: 0.9rem;
            }

            .back-link {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>✅ Quiz Submitted Successfully</h2>
        <p>Your answers have been recorded.</p>
        <p><strong>Results will be available on:</strong><br>
            <?php echo date("F j, Y \\a\\t g:i A", strtotime($result_date)); ?>
        </p>
        <a class="back-link" href="dashboard.php">Go to Dashboard</a>
    </div>
</body>
</html>