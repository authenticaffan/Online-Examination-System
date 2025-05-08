<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

// Fetch quizzes created by this teacher
$teacher_id = $_SESSION['teacher_id'];
$quizzes = mysqli_query($conn, "SELECT * FROM quizzes WHERE teacher_id = '$teacher_id'");

$message = "";
if (isset($_POST['submit'])) {
    $quiz_id = $_POST['quiz_id'];
    $question = $_POST['question'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $correct = $_POST['correct'];

    $query = "INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_option)
              VALUES ('$quiz_id', '$question', '$a', '$b', '$c', '$d', '$correct')";
    if (mysqli_query($conn, $query)) {
        $message = "✅ Question added successfully!";
    } else {
        $message = "❌ Error adding question: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Question</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-hover: #388E3C;
            --danger: #e74c3c;
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
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: var(--card-bg);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px var(--shadow);
        }

        h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 20px;
        }

        form label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        form select,
        form textarea,
        form input[type="text"],
        form input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #f3f4f6;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        form select:focus,
        form textarea:focus,
        form input[type="text"]:focus {
            border-color: var(--primary);
            background-color: #e0f7e9;
            outline: none;
        }

        form input[type="submit"] {
            background-color: var(--primary);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        form input[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .message.success {
            background-color: #e0f7e9;
            color: #2e7d32;
        }

        .message.error {
            background-color: #fbe9e7;
            color: #c62828;
        }

        .back-button {
            display: block;
            text-align: center;
            background-color: #6c757d;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            form input[type="text"],
            form textarea,
            form select,
            form input[type="submit"],
            .back-button {
                font-size: 0.9rem;
                padding: 10px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Question to Test</h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="quiz_id">Select Test:</label>
        <select name="quiz_id" id="quiz_id" required>
            <option value="">-- Select test --</option>
            <?php while ($quiz = mysqli_fetch_assoc($quizzes)) {
                echo "<option value='{$quiz['id']}'>{$quiz['title']}</option>";
            } ?>
        </select>

        <label for="question">Question:</label>
        <textarea name="question" id="question" rows="4" required></textarea>

        <label for="a">Option A:</label>
        <input type="text" name="a" id="a" required>

        <label for="b">Option B:</label>
        <input type="text" name="b" id="b" required>

        <label for="c">Option C:</label>
        <input type="text" name="c" id="c" required>

        <label for="d">Option D:</label>
        <input type="text" name="d" id="d" required>

        <label for="correct">Correct Option:</label>
        <select name="correct" id="correct" required>
            <option value="">-- Select --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <input type="submit" name="submit" value="Add Question">
    </form>

    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>