<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$message = "";

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

if (isset($_POST['create'])) {
    $title = sanitize(strtoupper($_POST['title']));
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
        $message = "❌ Result publish date must be after test end time.";
    } else {
        $stmt = $conn->prepare("INSERT INTO quizzes (
            teacher_id, subject_id, title, duration, result_publish_date, 
            year, section, branch, quiz_start_datetime, quiz_end_datetime
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("iisissssss", $teacher_id, $subject_id, $title, $duration, $result_date,
            $year, $section, $branch, $test_start, $test_end);

        if ($stmt->execute()) {
            $message = "✅ Test created successfully! You can now add questions.";
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
    <title>Create Test</title>
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
        form input[type="text"],
        form input[type="number"],
        form input[type="datetime-local"],
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
        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form input[type="datetime-local"]:focus {
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
            form input[type="number"],
            form input[type="datetime-local"],
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
    <script>
        function validateForm() {
            const title = document.forms["quizForm"]["title"].value.trim();
            const duration = document.forms["quizForm"]["duration"].value;
            const subject = document.forms["quizForm"]["subject"].value;
            const start = new Date(document.forms["quizForm"]["test_start_datetime"].value);
            const end = new Date(document.forms["quizForm"]["test_end_datetime"].value);
            const result = new Date(document.forms["quizForm"]["result_publish_date"].value);

            if (title === "" || duration === "" || subject === "") {
                alert("❌ Please fill all required fields.");
                return false;
            }

            if (start >= end) {
                alert("❌ Start date/time must be before end date/time.");
                return false;
            }

            if (end >= result) {
                alert("❌ Result publish date must be after the quiz end time.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Create Test</h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form name="quizForm" method="POST" onsubmit="return validateForm();">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>

        <label for="duration">Duration (minutes)</label>
        <input type="number" name="duration" id="duration" min="1" required>

        <label for="subject">Subject</label>
        <select name="subject" id="subject" required>
            <option value="">-- Select --</option>
            <?php while ($row = mysqli_fetch_assoc($subjects)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            } ?>
        </select>

        <label for="test_start_datetime">Test Start Date & Time</label>
        <input type="datetime-local" name="test_start_datetime" id="test_start_datetime" required>

        <label for="test_end_datetime">Test End Date & Time</label>
        <input type="datetime-local" name="test_end_datetime" id="test_end_datetime" required>

        <label for="result_publish_date">Result Publish Date</label>
        <input type="datetime-local" name="result_publish_date" id="result_publish_date" required>

        <label for="year">Year</label>
        <select name="year" id="year" required>
            <option value="">-- Select Year --</option>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
        </select>

        <label for="section">Section</label>
        <select name="section" id="section" required>
            <option value="">-- Select Section --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>

        <label for="branch">Branch</label>
        <select name="branch" id="branch" required>
            <option value="">-- Select Branch --</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Mechanical">Mechanical</option>
            <option value="Electrical">Electrical</option>
            <option value="Civil">Civil</option>
            <option value="Electronics">Electronics</option>
            <option value="Information Technology">Information Technology</option>
        </select>

        <input type="submit" name="create" value="Create Test">
        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </form>
</div>

</body>
</html>