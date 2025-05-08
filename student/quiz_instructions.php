<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$quiz_id = intval($_GET['quiz_id']); // Sanitize quiz ID

$quiz = mysqli_query($conn, "SELECT * FROM quizzes WHERE id='$quiz_id'")->fetch_assoc();
$current_time = date('Y-m-d H:i:s');

if ($current_time < $quiz['quiz_start_datetime'] || $current_time > $quiz['quiz_end_datetime']) {
    echo "<div style='max-width:700px;margin:30px auto;padding:20px;background:#fff3cd;border:1px solid #ffeeba;color:#856404;border-radius:8px;text-align:center;'>
        ⏳ <strong>This quiz is not currently available.</strong><br><br>
        Available from: <strong>" . date('d M Y h:i A', strtotime($quiz['quiz_start_datetime'])) . "</strong><br>
        Until: <strong>" . date('d M Y h:i A', strtotime($quiz['quiz_end_datetime'])) . "</strong>
    </div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Instructions - <?php echo htmlspecialchars($quiz['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --background: #f9fafb;
            --card-bg: #ffffff;
            --text: #1f2937;
            --shadow: rgba(0, 0, 0, 0.1);
            --warning-bg: #fff3cd;
            --warning-border: #ffeeba;
            --warning-text: #856404;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            background: var(--card-bg);
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: var(--primary);
        }

        .details {
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .details p {
            margin: 8px 0;
        }

        h3 {
            margin-top: 20px;
            font-size: 1.5rem;
        }

        ul {
            padding-left: 20px;
            margin-top: 10px;
            font-size: 1rem;
        }

        ul li {
            margin-bottom: 10px;
        }

        .start-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .start-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .warning {
            max-width: 700px;
            margin: 30px auto;
            padding: 20px;
            background: var(--warning-bg);
            border: 1px solid var(--warning-border);
            color: var(--warning-text);
            border-radius: 8px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            ul {
                font-size: 0.9rem;
            }

            .start-btn {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h2>

    <div class="details">
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($quiz['duration']); ?> minutes</p>
        <p><strong>Start Time:</strong> <?php echo date('d M Y h:i A', strtotime($quiz['quiz_start_datetime'])); ?></p>
        <p><strong>End Time:</strong> <?php echo date('d M Y h:i A', strtotime($quiz['quiz_end_datetime'])); ?></p>
    </div>

    <h3>📘 Rules & Regulations:</h3>
    <ul>
        <li>Once you start, the timer cannot be paused.</li>
        <li>Leaving fullscreen or switching tabs will be considered cheating.</li>
        <li>Make sure your internet connection is stable.</li>
        <li>Click "Start Test" only when you are ready.</li>
        <li>The test will be automatically submitted when time runs out.</li>
    </ul>

    <?php $_SESSION['can_take_quiz'] = true; ?>
    <a href="take_quiz.php?quiz_id=<?php echo $quiz_id; ?>" class="start-btn">Start Test</a>
</div>
</body>
</html>