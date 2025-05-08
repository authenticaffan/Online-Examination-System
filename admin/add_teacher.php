<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_teacher'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if username already exists
    $checkQuery = "SELECT * FROM teachers WHERE username = '$username'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $message = array('type' => 'error', 'text' => 'Error: Username already exists.');
    } else {
        $insertQuery = "INSERT INTO teachers (name, username, password, status) VALUES ('$name', '$username', '$password', 'active')";
        if (mysqli_query($conn, $insertQuery)) {
            $message = array('type' => 'success', 'text' => 'Teacher added successfully!');
        } else {
            $message = array('type' => 'error', 'text' => 'Error: ' . mysqli_error($conn));
        }
    }
}

include("../includes/admin-header.php");
?>

<div class="mx-auto" style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Add Teacher</h2>
            <p class="card-description">Create a new teacher account for your educational platform</p>
        </div>
        <div class="card-content">
            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $message['type'] == 'success' ? 'success' : 'error'; ?>">
                    <i class="fas fa-<?php echo $message['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo $message['text']; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Teacher Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Enter teacher's full name" required>
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input" placeholder="Enter teacher's username" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-input" placeholder="Enter teacher's email" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter password" required>
                    <p class="form-note">Make sure the password is secure and unique.</p>
                </div>

                <div class="flex gap-4 mt-4">
                    <button type="submit" name="add_teacher" class="btn btn-primary w-full">Add Teacher</button>
                    <a href="dashboard.php" class="btn btn-outline w-full">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../includes/admin-footer.php"); ?>
