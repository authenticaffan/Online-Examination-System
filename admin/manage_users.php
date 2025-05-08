<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch teachers and students
$teachers_query = "SELECT * FROM teachers";
$teachers = mysqli_query($conn, $teachers_query);

$students_query = "SELECT * FROM students";
$students = mysqli_query($conn, $students_query);

// Delete teacher and related data
if (isset($_POST['delete_teacher'])) {
    $teacher_id = $_POST['teacher_id'];

    // Get all quizzes by this teacher
    $quiz_ids = [];
    $quiz_query = mysqli_query($conn, "SELECT id FROM quizzes WHERE teacher_id = $teacher_id");
    while ($row = mysqli_fetch_assoc($quiz_query)) {
        $quiz_ids[] = $row['id'];
    }

    if (!empty($quiz_ids)) {
        $quiz_ids_str = implode(',', $quiz_ids);

        // Delete related questions
        mysqli_query($conn, "DELETE FROM questions WHERE quiz_id IN ($quiz_ids_str)");

        // Delete related student answers
        mysqli_query($conn, "DELETE FROM student_answers WHERE quiz_id IN ($quiz_ids_str)");

        // Delete related results
        mysqli_query($conn, "DELETE FROM results WHERE quiz_id IN ($quiz_ids_str)");

        // Delete the quizzes
        mysqli_query($conn, "DELETE FROM quizzes WHERE id IN ($quiz_ids_str)");
    }

    // Delete the teacher
    $stmt = mysqli_prepare($conn, "DELETE FROM teachers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $teacher_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to refresh the page
    header("Location: manage_users.php");
    exit();
}

// Delete student and related data
if (isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];

    // Delete from student_answers
    mysqli_query($conn, "DELETE FROM student_answers WHERE student_id = $student_id");

    // Delete from results
    mysqli_query($conn, "DELETE FROM results WHERE student_id = $student_id");

    // Delete the student
    $stmt = mysqli_prepare($conn, "DELETE FROM students WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to refresh the page
    header("Location: manage_users.php");
    exit();
}

// Block/unblock teachers
if (isset($_POST['block_teacher']) || isset($_POST['unblock_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $status = isset($_POST['block_teacher']) ? 'blocked' : 'active';

    $stmt = mysqli_prepare($conn, "UPDATE teachers SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $teacher_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to refresh the page
    header("Location: manage_users.php");
    exit();
}

// Block/unblock students
if (isset($_POST['block_student']) || isset($_POST['unblock_student'])) {
    $student_id = $_POST['student_id'];
    $status = isset($_POST['block_student']) ? 'blocked' : 'active';

    $stmt = mysqli_prepare($conn, "UPDATE students SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $student_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to refresh the page
    header("Location: manage_users.php");
    exit();
}

include("../includes/admin-header.php");
?>

<div>
    <h1 class="text-3xl font-bold mb-4">Manage Users</h1>
    <p class="text-muted-foreground mb-6">Manage teachers and students on your platform</p>

    <div class="tabs">
        <div class="tabs-list">
            <button class="tabs-trigger active" data-tab="teachers-tab">Teachers</button>
            <button class="tabs-trigger" data-tab="students-tab">Students</button>
        </div>

        <div id="teachers-tab" class="tabs-content active">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Teachers</h2>
                    <p class="card-description">Manage teacher accounts and permissions</p>
                </div>
                <div class="card-content">
                    <?php if (mysqli_num_rows($teachers) > 0): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($teacher = mysqli_fetch_assoc($teachers)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($teacher['username']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $teacher['status'] == 'active' ? 'badge-outline' : 'badge-destructive'; ?>">
                                                    <?php echo ucfirst($teacher['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <form method="POST">
                                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
                                                        <?php if ($teacher['status'] == 'active'): ?>
                                                            <button type="submit" name="block_teacher" class="btn btn-destructive" style="padding: 6px 12px; font-size: 12px;">
                                                                <i class="fas fa-ban"></i> Block
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="submit" name="unblock_teacher" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                                                                <i class="fas fa-check-circle"></i> Unblock
                                                            </button>
                                                        <?php endif; ?>
                                                    </form>
                                                    <form method="POST">
                                                        <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
                                                        <button type="submit" name="delete_teacher" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;" data-confirm="Are you sure you want to delete this teacher and all related data?">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 32px; text-align: center; border: 1px dashed var(--border); border-radius: var(--radius);">
                            <p class="text-muted-foreground">No teachers found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div id="students-tab" class="tabs-content">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Students</h2>
                    <p class="card-description">Manage student accounts and permissions</p>
                </div>
                <div class="card-content">
                    <?php if (mysqli_num_rows($students) > 0): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $student['status'] == 'active' ? 'badge-outline' : 'badge-destructive'; ?>">
                                                    <?php echo ucfirst($student['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <form method="POST">
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <?php if ($student['status'] == 'active'): ?>
                                                            <button type="submit" name="block_student" class="btn btn-destructive" style="padding: 6px 12px; font-size: 12px;">
                                                                <i class="fas fa-ban"></i> Block
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="submit" name="unblock_student" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                                                                <i class="fas fa-check-circle"></i> Unblock
                                                            </button>
                                                        <?php endif; ?>
                                                    </form>
                                                    <form method="POST">
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <button type="submit" name="delete_student" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;" data-confirm="Are you sure you want to delete this student and all related data?">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 32px; text-align: center; border: 1px dashed var(--border); border-radius: var(--radius);">
                            <p class="text-muted-foreground">No students found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php include("../includes/admin-footer.php"); ?>
