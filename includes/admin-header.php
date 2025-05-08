<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <button class="sidebar-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="sidebar-header">
                <a href="dashboard.php" class="sidebar-logo">
                    <i class="fas fa-shield-alt"></i>
                    <span>Admin Panel</span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <ul class="sidebar-nav-list">
                    <li class="sidebar-nav-item">
                        <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="add_teacher.php" class="<?php echo $current_page == 'add_teacher.php' ? 'active' : ''; ?>">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Teacher</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="manage_users.php" class="<?php echo $current_page == 'manage_users.php' ? 'active' : ''; ?>">
                            <i class="fas fa-users-cog"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="view_quizzes.php" class="<?php echo $current_page == 'view_quizzes.php' ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt"></i>
                            <span>View Tests</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <header class="header">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center ml-auto">
                    <!-- You can add header elements here like notifications, user profile, etc. -->
                </div>
            </header>
            <div class="content">
