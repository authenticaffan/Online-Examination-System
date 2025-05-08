<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$now = date("Y-m-d H:i:s");

$quiz_filter = "";
if (isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);
    $quiz_filter = "AND q.id = $quiz_id";
}

$results = mysqli_query($conn, "
    SELECT r.quiz_id, r.score, r.cheated, q.title, q.result_publish_date
    FROM results r
    JOIN quizzes q ON r.quiz_id = q.id
    WHERE r.student_id = '$student_id' $quiz_filter
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Test Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --pass-color: #4CAF50;
            --fail-color: #d9534f;
            --cheat-color: #dc3545;
            --pending-color: #f0ad4e;
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --text-color: #333;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: var(--pass-color);
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .result-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .result-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .quiz-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .status {
            font-size: 1rem;
            margin-top: 5px;
        }

        .pass {
            color: var(--pass-color);
            font-weight: bold;
        }

        .fail {
            color: var(--fail-color);
            font-weight: bold;
        }

        .cheated {
            color: var(--cheat-color);
            font-weight: bold;
            font-style: italic;
        }

        .pending {
            color: var(--pending-color);
            font-weight: bold;
        }

        .score-line {
            margin-top: 8px;
            font-size: 0.95rem;
        }

        .no-results {
            text-align: center;
            color: var(--fail-color);
            font-size: 1.2rem;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .result-card {
                padding: 15px 20px;
            }

            h2 {
                font-size: 1.8rem;
            }

            .quiz-title {
                font-size: 1rem;
            }

            .score-line {
                font-size: 0.9rem;
            }

            .status {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }

            .quiz-title {
                font-size: 0.9rem;
            }

            .score-line, .status {
                font-size: 0.85rem;
            }

            .result-card {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Test Results</h2>

    <?php if (mysqli_num_rows($results) > 0) { ?>
        <?php while ($result = mysqli_fetch_assoc($results)) {
            $quiz_id = $result['quiz_id'];
            $question_count_res = mysqli_query($conn, "SELECT COUNT(*) AS total_questions FROM questions WHERE quiz_id = '$quiz_id'");
            $q_data = mysqli_fetch_assoc($question_count_res);
            $total_questions = $q_data['total_questions'] ?? 0;
            $total_marks = $total_questions > 0 ? $total_questions : 100;
            $score = $result['score'];
            $percent = ($score / $total_marks) * 100;
            $status = $percent < 35 ? "fail" : "pass";
        ?>
            <div class="result-card">
                <div class="quiz-title">📘 <?php echo htmlspecialchars($result['title']); ?></div>
                <?php if ($now >= $result['result_publish_date']) { ?>
                    <?php if ($result['cheated']) { ?>
                        <div class="status cheated">❗ Cheating detected — score invalid</div>
                    <?php } else { ?>
                        <div class="status <?php echo $status; ?>">
                            <?php echo strtoupper($status); ?> — 
                            <?php echo round($percent); ?>%
                        </div>
                        <div class="score-line">Score: <?php echo "$score / $total_marks"; ?></div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="status pending">
                        ⏳ Result will be available on: 
                        <?php echo date("F j, Y g:i A", strtotime($result['result_publish_date'])); ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="no-results">⚠️ You have no test results available.</p>
    <?php } ?>
</div>

</body>
</html>