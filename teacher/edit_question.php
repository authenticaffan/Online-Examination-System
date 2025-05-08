<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$message = "";

// Check if quiz ID and question ID are provided
if (isset($_GET['quiz_id']) && isset($_GET['id'])) {
    $quiz_id = $_GET['quiz_id'];
    $question_id = $_GET['id'];

    // Fetch quiz and question details to ensure they belong to the current teacher
    $quiz_result = mysqli_query($conn, "SELECT * FROM quizzes WHERE id = '$quiz_id' AND teacher_id = '$teacher_id'");
    $quiz = mysqli_fetch_assoc($quiz_result);

    if (!$quiz) {
        header("Location: dashboard.php");
        exit();
    }

    $question_result = mysqli_query($conn, "SELECT * FROM questions WHERE id = '$question_id' AND quiz_id = '$quiz_id'");
    $question = mysqli_fetch_assoc($question_result);

    if (!$question) {
        header("Location: view_questions.php?quiz_id=$quiz_id");
        exit();
    }

    $question_text = $question['question'];
    $option_a = $question['option_a'];
    $option_b = $question['option_b'];
    $option_c = $question['option_c'];
    $option_d = $question['option_d'];
    $correct_answer = $question['correct_option'];
} else {
    header("Location: dashboard.php");
    exit();
}

// Process form submission for updating the question
if (isset($_POST['update'])) {
    $question_text = htmlspecialchars(trim($_POST['question_text']));
    $option_a = htmlspecialchars(trim($_POST['option_a']));
    $option_b = htmlspecialchars(trim($_POST['option_b']));
    $option_c = htmlspecialchars(trim($_POST['option_c']));
    $option_d = htmlspecialchars(trim($_POST['option_d']));
    $correct_answer = $_POST['correct_answer'];

    // Validate question and options
    if (empty($question_text) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d)) {
        $message = "❌ All fields are required.";
    } else {
        // Update the question in the database
        $update_query = "UPDATE questions SET question = '$question_text', option_a = '$option_a', option_b = '$option_b', option_c = '$option_c', option_d = '$option_d', correct_option = '$correct_answer' WHERE id = '$question_id' AND quiz_id = '$quiz_id'";

        if (mysqli_query($conn, $update_query)) {
            $message = "✅ Question updated successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 16px;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form-group textarea {
            height: 120px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            border: none;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #4CAF50;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #45a049;
        }

        .form-actions {
            text-align: right;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
    <script>
        function validateForm() {
            const questionText = document.getElementById('question_text').value.trim();
            const optionA = document.getElementById('option_a').value.trim();
            const optionB = document.getElementById('option_b').value.trim();
            const optionC = document.getElementById('option_c').value.trim();
            const optionD = document.getElementById('option_d').value.trim();

            if (!questionText || !optionA || !optionB || !optionC || !optionD) {
                alert("❌ All fields are required.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Edit Question for Test: <?php echo htmlspecialchars($quiz['title']); ?></h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm();">
        <div class="form-group">
            <label for="question_text">Question Text</label>
            <textarea name="question_text" id="question_text" required><?php echo htmlspecialchars($question_text); ?></textarea>
        </div>

        <div class="form-group">
            <label for="option_a">Option A</label>
            <input type="text" name="option_a" id="option_a" value="<?php echo htmlspecialchars($option_a); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_b">Option B</label>
            <input type="text" name="option_b" id="option_b" value="<?php echo htmlspecialchars($option_b); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_c">Option C</label>
            <input type="text" name="option_c" id="option_c" value="<?php echo htmlspecialchars($option_c); ?>" required>
        </div>

        <div class="form-group">
            <label for="option_d">Option D</label>
            <input type="text" name="option_d" id="option_d" value="<?php echo htmlspecialchars($option_d); ?>" required>
        </div>

        <div class="form-group">
            <label for="correct_answer">Correct Answer</label>
            <select name="correct_answer" id="correct_answer" required>
                <option value="A" <?php echo ($correct_answer == 'A') ? 'selected' : ''; ?>>Option A</option>
                <option value="B" <?php echo ($correct_answer == 'B') ? 'selected' : ''; ?>>Option B</option>
                <option value="C" <?php echo ($correct_answer == 'C') ? 'selected' : ''; ?>>Option C</option>
                <option value="D" <?php echo ($correct_answer == 'D') ? 'selected' : ''; ?>>Option D</option>
            </select>
        </div>

        <div class="form-actions">
            <input type="submit" name="update" value="Update Question" class="btn">
        </div>
    </form>

    <a href="view_question.php?quiz_id=<?php echo $quiz_id; ?>" class="btn-back">Back to Questions</a>
</div>

</body>
</html>