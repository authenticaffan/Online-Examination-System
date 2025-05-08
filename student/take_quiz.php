<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

// Guard: Only allow access from quiz_instructions.php
if (!isset($_SESSION['can_take_quiz']) || !isset($_GET['quiz_id'])) {
    echo "Access denied. Please start the test from the instructions page.";
    exit();
}
unset($_SESSION['can_take_quiz']); // Remove the access key

$student_id = $_SESSION['student_id'];
$quiz_id = intval($_GET['quiz_id']); // Sanitize quiz ID

$quiz = mysqli_query($conn, "SELECT * FROM quizzes WHERE id='$quiz_id'")->fetch_assoc();
$current_time = date('Y-m-d H:i:s');

// Check availability window
if ($current_time < $quiz['quiz_start_datetime'] || $current_time > $quiz['quiz_end_datetime']) {
    echo "<div style='max-width:700px;margin:30px auto;padding:20px;background:#fff3cd;border:1px solid #ffeeba;color:#856404;border-radius:8px;text-align:center;'>
        ⏳ <strong>This Test is not currently available.</strong><br><br>
        Available from: <strong>" . date('d M Y h:i A', strtotime($quiz['quiz_start_datetime'])) . "</strong><br>
        Until: <strong>" . date('d M Y h:i A', strtotime($quiz['quiz_end_datetime'])) . "</strong>
    </div>";
    exit();
}

$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id='$quiz_id' ORDER BY RAND()");
$duration = $quiz['duration'] * 60; // seconds
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Test - <?php echo htmlspecialchars($quiz['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-hover: #388E3C;
            --danger: #ef4444;
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
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: var(--card-bg);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow);
        }

        h2 {
            text-align: center;
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .timer {
            font-size: 1.2rem;
            color: var(--danger);
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .question {
            margin-bottom: 20px;
        }

        .question p {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .question label {
            display: block;
            margin: 8px 0;
            cursor: pointer;
            font-size: 1rem;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .question label:hover {
            background-color: #f0f0f0;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        input[type="submit"] {
            background-color: var(--primary);
            color: white;
            padding: 14px;
            font-size: 1.1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.5rem;
            }

            .timer {
                font-size: 1rem;
            }

            .question p {
                font-size: 1rem;
            }

            input[type="submit"] {
                font-size: 1rem;
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                font-size: 1.3rem;
            }

            .timer {
                font-size: 0.9rem;
            }

            .question p {
                font-size: 0.9rem;
            }

            input[type="submit"] {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Test: <?php echo htmlspecialchars($quiz['title']); ?></h2>
    <div id="timer" class="timer">Time Left: Loading...</div>

    <form action="submit_quiz.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

        <?php $i = 1; while ($question = mysqli_fetch_assoc($questions)) { ?>
            <div class="question">
                <p><?php echo $i++ . ". " . htmlspecialchars($question['question']); ?></p>
                <label><input type="radio" name="q<?php echo $question['id']; ?>" value="A" required> <?php echo htmlspecialchars($question['option_a']); ?></label>
                <label><input type="radio" name="q<?php echo $question['id']; ?>" value="B"> <?php echo htmlspecialchars($question['option_b']); ?></label>
                <label><input type="radio" name="q<?php echo $question['id']; ?>" value="C"> <?php echo htmlspecialchars($question['option_c']); ?></label>
                <label><input type="radio" name="q<?php echo $question['id']; ?>" value="D"> <?php echo htmlspecialchars($question['option_d']); ?></label>
            </div>
        <?php } ?>

        <input type="submit" value="Submit test">
    </form>
</div>

<script>
    var duration = <?php echo $duration; ?>;
    var timer = document.getElementById('timer');
    var submitting = false;

    function updateTimer() {
        var minutes = Math.floor(duration / 60);
        var seconds = duration % 60;
        timer.innerHTML = "Time Left: " + minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";

        if (duration <= 0) {
            alert('⏰ Time is up! Submitting your test.');
            submitting = true;
            document.forms[0].submit();
        }
        duration--;
    }

    updateTimer();
    setInterval(updateTimer, 1000);

    document.forms[0].addEventListener("submit", function () {
        submitting = true;
    });

    document.addEventListener('visibilitychange', function() {
        if (document.hidden && !submitting) {
            alert("🚫 Cheating detected (Tab Switch). You have been logged out.");
            window.location.href = "logout.php?cheated=1&quiz_id=<?php echo $quiz_id; ?>";
        }
    });

    document.addEventListener("fullscreenchange", () => {
        if (!document.fullscreenElement && !submitting) {
            alert("🚫 Cheating detected (Exited Fullscreen). You have been logged out.");
            window.location.href = "logout.php?cheated=1&quiz_id=<?php echo $quiz_id; ?>";
        }
    });
</script>
</body>
</html>