<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Get current student's data
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE id = '$student_id'");
$student = mysqli_fetch_assoc($student_query);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $register_number = mysqli_real_escape_string($conn, trim($_POST['register_number']));
    $mobile_number = mysqli_real_escape_string($conn, trim($_POST['mobile_number']));
    $year = mysqli_real_escape_string($conn, trim($_POST['year']));
    $section = mysqli_real_escape_string($conn, trim($_POST['section']));
    $branch = mysqli_real_escape_string($conn, trim($_POST['branch']));

    $update_query = "UPDATE students SET 
                        name = '$name',
                        email = '$email',
                        register_number = '$register_number', 
                        mobile_number = '$mobile_number',
                        year = '$year',
                        section = '$section',
                        branch = '$branch'
                    WHERE id = '$student_id'";

    if (mysqli_query($conn, $update_query)) {
        $success_message = "Profile updated successfully!";
        $student_query = mysqli_query($conn, "SELECT * FROM students WHERE id = '$student_id'");
        $student = mysqli_fetch_assoc($student_query);
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
    }
}

// Current time
$current_time = date('Y-m-d H:i:s');

// Fetch upcoming quizzes (not attended and not expired)
$upcoming_quizzes = mysqli_query($conn, "
    SELECT q.id, q.title, q.quiz_end_datetime
    FROM quizzes q
    WHERE q.year = '{$student['year']}'
      AND q.section = '{$student['section']}'
      AND q.branch = '{$student['branch']}'
      AND q.id NOT IN (
          SELECT quiz_id FROM results WHERE student_id = '$student_id'
      )
      AND q.quiz_end_datetime > '$current_time'
");

// Fetch completed quizzes (attended or expired but not attended)
$completed_quizzes = mysqli_query($conn, "
    SELECT q.id, q.title,
        CASE 
            WHEN r.quiz_id IS NOT NULL THEN 'attended'
            WHEN r.quiz_id IS NULL AND q.quiz_end_datetime <= '$current_time' THEN 'missed'
            ELSE 'upcoming'
        END AS status
    FROM quizzes q
    LEFT JOIN results r ON q.id = r.quiz_id AND r.student_id = '$student_id'
    WHERE q.year = '{$student['year']}'
      AND q.section = '{$student['section']}'
      AND q.branch = '{$student['branch']}'
      AND (
            r.quiz_id IS NOT NULL 
            OR (r.quiz_id IS NULL AND q.quiz_end_datetime <= '$current_time')
      )
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #333;
        }

        /* Flex container */
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #1e293b;
            color: white;
            padding: 30px 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 25px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .sidebar .logout-btn {
            background-color: #ef4444;
            border: none;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-top: 20px;
            display: block;
            font-weight: bold;
        }
        .sidebar .logout-btn:hover {
            background-color: #dc2626;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            flex-grow: 1;
            transition: margin 0.3s ease-in-out;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }
        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: #333;
            margin: 4px 0;
        }

        /* Form and quiz styles */
        .profile-update-form, .quiz-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .profile-update-form input,
        .profile-update-form button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .quiz-list {
            list-style: none;
            padding: 0;
        }
        .quiz-list li {
            background: #f0f4f8;
            margin-bottom: 12px;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .quiz-list a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }
        .result-link {
            color: #28a745;
            font-weight: bold;
        }
        .success-message, .error-message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .success-message {
            background-color: #28a745;
            color: white;
        }
        .error-message {
            background-color: #dc2626;
            color: white;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .hamburger {
                display: flex;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .header h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>Student Dashboard</h2>
        <a href="dashboard.php">Home</a>
        <a href="dashboard.php?section=profile">Profile</a>
        <a href="dashboard.php?section=upcoming">Upcoming Tests</a>
        <a href="dashboard.php?section=completed">Completed Tests</a>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Welcome, <?php echo $student['name']; ?></h2>
            <div style="color:black;" class="hamburger" onclick="toggleSidebar()">
                <div></div><div></div><div></div>
            </div>
        </div>

        <?php
        $section = $_GET['section'] ?? '';

        if ($section == 'profile') {
            ?>
            <div class="profile-update-form">
                <h3>Your Profile</h3>
                <?php if (isset($success_message)) echo "<div class='success-message'>$success_message</div>"; ?>
                <?php if (isset($error_message)) echo "<div class='error-message'>$error_message</div>"; ?>
                <form method="POST">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $student['name']; ?>" required placeholder="Name">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>" required placeholder="Email">

                    <label for="register_number">Register Number</label>
                    <input type="text" id="register_number" name="register_number" value="<?php echo $student['register_number']; ?>" required placeholder="Register Number">

                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $student['mobile_number']; ?>" required placeholder="Mobile Number">

                    <label for="year">Year</label>
                    <input type="text" id="year" name="year" value="<?php echo $student['year']; ?>" required placeholder="Year">

                    <label for="section">Section</label>
                    <input type="text" id="section" name="section" value="<?php echo $student['section']; ?>" required placeholder="Section">

                    <label for="branch">Branch</label>
                    <input type="text" id="branch" name="branch" value="<?php echo $student['branch']; ?>" required placeholder="Branch">

                    <button type="submit" name="update_profile">Update Profile</button>
                </form>
            </div>
            <?php
        } elseif ($section == 'upcoming') {
            ?>
            <div class="quiz-card">
                <h3>Upcoming Tests</h3>
                <ul class="quiz-list">
                    <?php 
                    if (mysqli_num_rows($upcoming_quizzes) > 0) {
                        while($quiz = mysqli_fetch_assoc($upcoming_quizzes)) {
                            echo "<li><a href='quiz_instructions.php?quiz_id={$quiz['id']}'>{$quiz['title']}</a></li>";
                        }
                    } else {
                        echo "<li>No upcoming tests.</li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
        } elseif ($section == 'completed') {
            ?>
            <div class="quiz-card">
                <h3>Completed Tests</h3>
                <ul class="quiz-list">
                    <?php 
                    if (mysqli_num_rows($completed_quizzes) > 0) {
                        while($quiz = mysqli_fetch_assoc($completed_quizzes)) {
                            if ($quiz['status'] === 'attended') {
                                echo "<li>{$quiz['title']} <a class='result-link' href='view_result.php?quiz_id={$quiz['id']}'>View Result</a></li>";
                            } elseif ($quiz['status'] === 'missed') {
                                echo "<li>{$quiz['title']} <span style='color: #dc3545; font-weight: bold;'>not attended</span></li>";
                            }
                        }
                    } else {
                        echo "<li>No tests completed yet.</li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
        } else {
            echo "<p style='margin-top:20px;font-size:18px;'>👋 Welcome to your dashboard! Use the sidebar to view your profile or take quizzes.</p>";
        }
        ?>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>
</body>
</html>