<?php
session_start();
include("../includes/db.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Invalid credentials! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-header login-header">
                <div class="login-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2 class="card-title">Admin Login</h2>
                <p class="card-description">Enter your credentials to access the admin panel</p>
            </div>
            <div class="card-content">
                <?php if (isset($login_error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $login_error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <div class="relative">
                            <label for="username" class="form-label">Username</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-muted-foreground">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="username" name="username" class="form-input" style="padding-left: 35px;" placeholder="Enter your username" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="relative">
                            <label for="password" class="form-label">Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-muted-foreground">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" class="form-input" style="padding-left: 35px;" placeholder="Enter your password" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-full">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
