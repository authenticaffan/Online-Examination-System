<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$message = "";
if (isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $teacher_id = $_SESSION['teacher_id'];
    if (mysqli_query($conn, "INSERT INTO subjects (name, teacher_id) VALUES ('$name', '$teacher_id')")) {
        $message = "✅ Subject added successfully!";
    } else {
        $message = "❌ Error adding subject: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Subject</title>
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
            max-width: 600px;
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
    <h2>Create Subject</h2>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Subject Name</label>
        <input type="text" name="name" id="name" placeholder="Enter subject name" required>

        <input type="submit" name="submit" value="Add Subject">
    </form>

    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
</div>

</body>
</html>