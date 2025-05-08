<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include("../includes/admin-header.php");
?>

<div>
    <h1 class="text-3xl font-bold mb-4">Welcome, Admin</h1>
    <p class="text-muted-foreground mb-6">Manage your educational platform from this dashboard</p>

    <div class="dashboard-grid">
        <a href="add_teacher.php" class="card dashboard-card">
            <div class="card-header">
                <div class="dashboard-card-icon" style="background-color: #dbeafe;">
                    <i class="fas fa-user-plus" style="color: #2563eb;"></i>
                </div>
                <h3 class="card-title">Add Teacher</h3>
                <p class="card-description">Create new teacher accounts</p>
            </div>
            <div class="card-content">
                <p class="text-sm text-muted-foreground">Click to access</p>
            </div>
        </a>

        <a href="manage_users.php" class="card dashboard-card">
            <div class="card-header">
                <div class="dashboard-card-icon" style="background-color: #f3e8ff;">
                    <i class="fas fa-users-cog" style="color: #9333ea;"></i>
                </div>
                <h3 class="card-title">Manage Users</h3>
                <p class="card-description">Manage teachers and students</p>
            </div>
            <div class="card-content">
                <p class="text-sm text-muted-foreground">Click to access</p>
            </div>
        </a>

        <a href="view_quizzes.php" class="card dashboard-card">
            <div class="card-header">
                <div class="dashboard-card-icon" style="background-color: #dcfce7;">
                    <i class="fas fa-file-alt" style="color: #16a34a;"></i>
                </div>
                <h3 class="card-title">View Tests</h3>
                <p class="card-description">Browse all available tests</p>
            </div>
            <div class="card-content">
                <p class="text-sm text-muted-foreground">Click to access</p>
            </div>
        </a>
    </div>
</div>

<?php include("../includes/admin-footer.php"); ?>
