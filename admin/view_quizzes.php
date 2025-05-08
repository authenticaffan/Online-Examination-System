<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Updated query to fetch quiz title, subject, teacher, and date
$query = "SELECT quizzes.id, quizzes.title, quizzes.subject_id, quizzes.created_at, 
                 subjects.name AS subject_name, 
                 teachers.username AS teacher_name
          FROM quizzes
          LEFT JOIN subjects ON quizzes.subject_id = subjects.id
          LEFT JOIN teachers ON quizzes.teacher_id = teachers.id
          ORDER BY quizzes.created_at DESC";
$result = mysqli_query($conn, $query);

include("../includes/admin-header.php");
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Tests</h2>
        <p class="card-description">Browse all tests created by teachers across different subjects</p>
    </div>
    <div class="card-content">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($quiz = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                                <td><?php echo htmlspecialchars($quiz['subject_name']); ?></td>
                                <td><?php echo htmlspecialchars($quiz['teacher_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo date('d M Y', strtotime($quiz['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="padding: 32px; text-align: center; border: 1px dashed var(--border); border-radius: var(--radius);">
                <p class="text-muted-foreground">No tests available.</p>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="dashboard.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php include("../includes/admin-footer.php"); ?>
