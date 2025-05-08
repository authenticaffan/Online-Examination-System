<?php
session_start();
include("../includes/db.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM students WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($res) == 1) {
        $data = mysqli_fetch_assoc($res);
        $_SESSION['student'] = $email;
        $_SESSION['student_id'] = $data['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #f3f4f6;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            background-color: #e0e7ff;
            outline: none;
        }

        input[type="submit"] {
            background-color: var(--primary);
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .error {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            background-color: var(--danger);
            color: #fff;
        }

        .info {
            padding: 10px;
            margin: 15px 0;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 500px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>

        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'cheated'): ?>
            <div class="info">
                🚫 You were logged out for violating exam rules (tab switching or exiting fullscreen). Please contact admin.
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" name="login" value="Login">
        </form>

        <div class="register-link">
            New user? <a href="../student/register.php">Register</a>
        </div>
    </div>
</body>
</html>