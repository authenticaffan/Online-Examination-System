<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$message = "";

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Check if quiz ID is provided in the URL
if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Fetch quiz details for the given quiz ID
    $quiz_result = mysqli_query($conn, "SELECT * FROM quizzes WHERE id = '$quiz_id' AND teacher_id = '$teacher_id'");
    $quiz = mysqli_fetch_assoc($quiz_result);

    // If no quiz found or quiz doesn't belong to the logged-in teacher
    if (!$quiz) {
        header("Location: dashboard.php");
        exit();
    }

    // Set form values from the existing quiz
    $title = $quiz['title'];
    $duration = $quiz['duration'];
    $subject_id = $quiz['subject_id'];
    $result_date = $quiz['result_publish_date'];
    $year = $quiz['year'];
    $section = $quiz['section'];
    $branch = $quiz['branch'];
    $test_start = $quiz['quiz_start_datetime'];
    $test_end = $quiz['quiz_end_datetime'];
} else {
    header("Location: dashboard.php");
    exit();
}

// Process form submission for updating quiz
if (isset($_POST['update'])) {
    $title = sanitize($_POST['title']);
    $duration = (int)$_POST['duration'];
    $subject_id = sanitize($_POST['subject']);
    $result_date = $_POST['result_publish_date'];
    $year = sanitize($_POST['year']);
    $section = sanitize($_POST['section']);
    $branch = sanitize($_POST['branch']);
    $test_start = $_POST['test_start_datetime'];
    $test_end = $_POST['test_end_datetime'];

    // Server-side datetime validation
    if (strtotime($test_start) >= strtotime($test_end)) {
        $message = "❌ Start time must be before end time.";
    } elseif (strtotime($test_end) >= strtotime($result_date)) {
        $message = "❌ Result publish date must be after quiz end time.";
    } else {
        // Update the quiz details
        $stmt = $conn->prepare("UPDATE quizzes SET 
            subject_id = ?, title = ?, duration = ?, result_publish_date = ?, 
            year = ?, section = ?, branch = ?, quiz_start_datetime = ?, quiz_end_datetime = ? 
            WHERE id = ? AND teacher_id = ?");
        $stmt->bind_param("isisssssssi", $subject_id, $title, $duration, $result_date, 
            $year, $section, $branch, $test_start, $test_end, $quiz_id, $teacher_id);

        if ($stmt->execute()) {
            $message = "✅ Quiz updated successfully!";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE teacher_id = '$teacher_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
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
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f5f7fb;
            color: #333;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        select {
            font-size: 16px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 24px;
            }

            input, select {
                font-size: 14px;
            }

            input[type="submit"] {
                font-size: 16px;
            }
        }
    </style>
    <script>
        function validateForm() {
            const title = document.getElementById('title').value.trim();
            const duration = document.getElementById('duration').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const start = document.getElementById('test_start_datetime').value.trim();
            const end = document.getElementById('test_end_datetime').value.trim();
            const result = document.getElementById('result_publish_date').value.trim();

            if (!title || !duration || !subject || !start || !end || !result) {
                alert("❌ Please fill all required fields.");
                return false;
            }

            if (new Date(start) >= new Date(end)) {
                alert("❌ Start time must be before end time.");
                return false;
            }

            if (new Date(end) >= new Date(result)) {
                alert("❌ Result publish date must be after the quiz end time.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Edit Test</h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form name="quizForm" method="POST" onsubmit="return validateForm();">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>

        <div class="form-group">
            <label for="duration">Duration (minutes)</label>
            <input type="number" name="duration" id="duration" value="<?php echo htmlspecialchars($duration); ?>" min="1" required>
        </div>

        <div class="form-group">
            <label for="subject">Subject</label>
            <select name="subject" id="subject" required>
                <option value="">-- Select --</option>
                <?php while ($row = mysqli_fetch_assoc($subjects)) {
                    echo "<option value='{$row['id']}'" . ($row['id'] == $subject_id ? ' selected' : '') . ">{$row['name']}</option>";
                } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="test_start_datetime">Test Start Date & Time</label>
            <input type="datetime-local" name="test_start_datetime" id="test_start_datetime" value="<?php echo htmlspecialchars($test_start); ?>" required>
        </div>

        <div class="form-group">
            <label for="test_end_datetime">Test End Date & Time</label>
            <input type="datetime-local" name="test_end_datetime" id="test_end_datetime" value="<?php echo htmlspecialchars($test_end); ?>" required>
        </div>

        <div class="form-group">
            <label for="result_publish_date">Result Publish Date</label>
            <input type="datetime-local" name="result_publish_date" id="result_publish_date" value="<?php echo htmlspecialchars($result_date); ?>" required>
        </div>

        <div class="form-group">
            <label for="year">Year</label>
            <select name="year" id="year" required>
                <option value="">-- Select Year --</option>
                <option value="1" <?php echo ($year == 1) ? 'selected' : ''; ?>>1st Year</option>
                <option value="2" <?php echo ($year == 2) ? 'selected' : ''; ?>>2nd Year</option>
                <option value="3" <?php echo ($year == 3) ? 'selected' : ''; ?>>3rd Year</option>
                <option value="4" <?php echo ($year == 4) ? 'selected' : ''; ?>>4th Year</option>
            </select>
        </div>

        <div class="form-group">
            <label for="section">Section</label>
            <select name="section" id="section" required>
                <option value="">-- Select Section --</option>
                <option value="A" <?php echo ($section == 'A') ? 'selected' : ''; ?>>A</option>
                <option value="B" <?php echo ($section == 'B') ? 'selected' : ''; ?>>B</option>
                <option value="C" <?php echo ($section == 'C') ? 'selected' : ''; ?>>C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="branch">Branch</label>
            <select name="branch" id="branch" required>
                <option value="">-- Select Branch --</option>
                <option value="Computer Science" <?php echo ($branch == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                <option value="Mechanical" <?php echo ($branch == 'Mechanical') ? 'selected' : ''; ?>>Mechanical</option>
                <option value="Electrical" <?php echo ($branch == 'Electrical') ? 'selected' : ''; ?>>Electrical</option>
                <option value="Civil" <?php echo ($branch == 'Civil') ? 'selected' : ''; ?>>Civil</option>
                <option value="Electronics" <?php echo ($branch == 'Electronics') ? 'selected' : ''; ?>>Electronics</option>
                <option value="Information Technology" <?php echo ($branch == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
            </select>
        </div>

        <input type="submit" name="update" value="Update Test">
        <a href="dashboard.php">Back to Dashboard</a>
    </form>
</div>

</body>
</html>