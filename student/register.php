<?php
session_start();
include("../includes/db.php");

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $register_number = trim($_POST['register_number']);
    $year = trim($_POST['year']);
    $section = trim($_POST['section']);
    $branch = trim($_POST['branch']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile = $_POST['mobile'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $check_email = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $error_message = "Email already exists!";
        } else {
            $query = "INSERT INTO students (name, email, register_number, year, section, branch, password, mobile_number) 
                      VALUES ('$name', '$email', '$register_number', '$year', '$section', '$branch', '$password', '$mobile')";
            if (mysqli_query($conn, $query)) {
                $success_message = "Registration successful!";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --danger: #ef4444;
            --success: #22c55e;
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
            min-height: 100vh;
        }

        .form-container {
            background: var(--card-bg);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #f3f4f6;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: var(--primary);
            background-color: #e0e7ff;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .error, .success {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
        }

        .error {
            background-color: var(--danger);
            color: white;
        }

        .success {
            background-color: var(--success);
            color: white;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 25px 20px;
            }

            input, select, input[type="submit"] {
                font-size: 15px;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 20px 15px;
            }

            input[type="submit"] {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Student Registration</h2>

        <?php if (!empty($error_message)) echo "<div class='error'>$error_message</div>"; ?>
        <?php if (!empty($success_message)) echo "<div class='success'>$success_message</div>"; ?>

        <form method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" required placeholder="Enter your name" autocomplete="name">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required placeholder="Enter your email" autocomplete="email">

            <label for="register_number">Register Number</label>
            <input type="text" name="register_number" id="register_number" required placeholder="Enter your register number">

            <label for="year">Year</label>
            <select name="year" id="year" required>
                <option value="" disabled selected>Select Year</option>
                <option value="1">First Year</option>
                <option value="2">Second Year</option>
                <option value="3">Third Year</option>
                <option value="4">Fourth Year</option>
            </select>

            <label for="section">Section</label>
            <select name="section" id="section" required>
                <option value="" disabled selected>Select Section</option>
                <option value="A">Section A</option>
                <option value="B">Section B</option>
            </select>

            <label for="branch">Branch</label>
            <select name="branch" id="branch" required>
                <option value="" disabled selected>Select Branch</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Information Technology">Information Technology</option>
                <option value="Electronics">Electronics</option>
                <option value="Electrical">Electrical</option>
                <option value="Mechanical">Mechanical</option>
                <option value="Civil">Civil</option>
            </select>

            <label for="mobile">Mobile Number</label>
            <input type="text" name="mobile" id="mobile" required placeholder="Enter your mobile number" autocomplete="tel">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required placeholder="Enter your password" autocomplete="new-password">

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm your password" autocomplete="new-password">

            <input type="submit" name="register" value="Register">
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Sign In</a>
        </div>
    </div>
</body>
</html>