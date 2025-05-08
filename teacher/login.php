<?php
session_start();
include("../includes/db.php");

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Fetch teacher details
    $res = mysqli_query($conn, "SELECT * FROM teachers WHERE username='$username' AND password='$password' AND status='active'");
    if (mysqli_num_rows($res) == 1) {
        $_SESSION['teacher'] = $username;
        $_SESSION['teacher_id'] = mysqli_fetch_assoc($res)['id'];
        header("Location:dashboard.php");
        exit();
    } else {
        $login_error = "Invalid credentials! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
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

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            top: 14px;
            left: 12px;
            color: #aaa;
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 38px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: var(--primary);
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
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: var(--primary-dark);
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
            background-color: var(--danger);
            color: #fff;
        }

        @media screen and (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }
        }

        @media screen and (min-width: 768px) {
            .container {
                max-width: 500px;
                padding: 40px;
            }

            h2 {
                font-size: 28px;
            }

            .input-group input {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-chalkboard-teacher"></i> Teacher Login</h2>

        <?php if (isset($login_error)): ?>
            <div class="alert"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>