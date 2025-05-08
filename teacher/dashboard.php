<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch quizzes for the teacher
$quizzes_query = mysqli_query($conn, "SELECT * FROM quizzes WHERE teacher_id = '$teacher_id'");

// Fetch subjects for the teacher
$subjects_query = mysqli_query($conn, "SELECT * FROM subjects WHERE teacher_id = '$teacher_id'");

if (isset($_GET['delete_quiz'])) {
    $quiz_id = $_GET['delete_quiz'];
    mysqli_query($conn, "DELETE FROM quizzes WHERE id = '$quiz_id' AND teacher_id = '$teacher_id'");
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_subject'])) {
    $subject_id = $_GET['delete_subject'];
    mysqli_query($conn, "DELETE FROM subjects WHERE id = '$subject_id' AND teacher_id = '$teacher_id'");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --destructive: #ef4444;
            --destructive-hover: #dc2626;
            --background: #ffffff;
            --foreground: #0f172a;
            --muted: #f1f5f9;
            --muted-foreground: #64748b;
            --card: #ffffff;
            --card-foreground: #0f172a;
            --border: #e2e8f0;
            --input: #e2e8f0;
            --ring: #93c5fd;
            --radius: 0.5rem;
            --sidebar-width: 280px;
            --sidebar-background: #1e293b;
            --sidebar-foreground: #f8fafc;
            --sidebar-border: #334155;
            --sidebar-accent: #334155;
            --sidebar-accent-foreground: #f8fafc;
            --status-upcoming: #f59e0b;
            --status-ongoing: #10b981;
            --status-completed: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--foreground);
            line-height: 1.5;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-background);
            color: var(--sidebar-foreground);
            padding: 0;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu-item {
            margin-bottom: 0.25rem;
        }

        .sidebar-menu-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            width: 100%;
            text-align: left;
            border: none;
            background: transparent;
            color: var(--sidebar-foreground);
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .sidebar-menu-button:hover {
            background-color: var(--sidebar-accent);
        }

        .sidebar-menu-button.active {
            background-color: var(--sidebar-accent);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--sidebar-border);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--destructive);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-logout:hover {
            background-color: var(--destructive-hover);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            width: calc(100% - var(--sidebar-width));
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background-color: var(--background);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h1 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            background: none;
            border: none;
        }

        .hamburger div {
            width: 25px;
            height: 2px;
            background-color: var(--foreground);
            margin: 4px 0;
            transition: 0.3s;
        }

        .content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-message {
            margin-bottom: 2rem;
            color: var(--muted-foreground);
        }

        .section {
            margin-bottom: 2.5rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Cards */
        .card {
            background-color: var(--card);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
        }

        .card-header {
            padding: 1.25rem 1.5rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .card-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-top: 0.25rem;
        }

        .card-content {
            padding: 1.25rem 1.5rem;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
        }

        .badge-upcoming {
            background-color: var(--status-upcoming);
        }

        .badge-ongoing {
            background-color: var(--status-ongoing);
        }

        .badge-completed {
            background-color: var(--status-completed);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--foreground);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background-color: var(--muted);
        }

        .btn-ghost {
            background-color: transparent;
            color: var(--foreground);
        }

        .btn-ghost:hover {
            background-color: var(--muted);
        }

        .btn-destructive {
            background-color: var(--destructive);
        }

        .btn-destructive:hover {
            background-color: var(--destructive-hover);
        }

        /* Collapsible */
        .collapsible-content {
            display: none;
            background-color: var(--muted);
            border-radius: 0.375rem;
            margin-top: 0.5rem;
        }

        .collapsible-content.open {
            display: block;
        }

        /* Quiz Details */
        .quiz-details {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .quiz-details {
                grid-template-columns: 1fr 1fr;
            }
        }

        .quiz-detail-item {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .quiz-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        /* Empty States */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--muted-foreground);
            margin-bottom: 1rem;
        }

        .empty-state-text {
            color: var(--muted-foreground);
            margin-bottom: 1rem;
        }

        /* Subject Grid */
        .subject-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .subject-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .subject-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Modal */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
        }

        .modal-backdrop.open {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background-color: var(--background);
            border-radius: var(--radius);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-description {
            color: var(--muted-foreground);
            margin-bottom: 1.5rem;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            border-top: 1px solid var(--border);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .hamburger {
                display: flex;
            }

            .header {
                padding: 1rem;
            }

            .content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Teacher Dashboard</h2>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="dashboard.php" class="sidebar-menu-button active">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="create_subject.php" class="sidebar-menu-button">
                        <i class="fas fa-book"></i>
                        <span>Create New Subject</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="create_quiz.php" class="sidebar-menu-button">
                        <i class="fas fa-file-alt"></i>
                        <span>Create New Test</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="add_questions.php" class="sidebar-menu-button">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Questions</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="view_results.php" class="sidebar-menu-button">
                        <i class="fas fa-chart-bar"></i>
                        <span>View Results</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <a href="../logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <button class="hamburger" onclick="toggleSidebar()">
                <div></div>
                <div></div>
                <div></div>
            </button>
            <h1>Welcome, <?php echo $_SESSION['teacher']; ?></h1>
        </div>

        <div class="content">
            <div class="welcome-message">
                <p>Manage your tests and subjects from here.</p>
            </div>

            <!-- Quizzes Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Your Tests</h2>
                    <a href="create_quiz.php" class="btn">
                        <i class="fas fa-plus-circle"></i>
                        Create New Test
                    </a>
                </div>

                <?php if (mysqli_num_rows($quizzes_query) > 0) { ?>
                    <?php while ($quiz = mysqli_fetch_assoc($quizzes_query)) {
                        $subject_query = mysqli_query($conn, "SELECT * FROM subjects WHERE teacher_id = '$teacher_id' AND id = '{$quiz['subject_id']}'");
                        $subject = mysqli_fetch_assoc($subject_query);

                        // Calculate status
                        $now = new DateTime();
                        $start = new DateTime($quiz['quiz_start_datetime']);
                        $end = new DateTime($quiz['quiz_end_datetime']);

                        if ($now < $start) {
                            $status = "Upcoming";
                            $statusClass = "badge-upcoming";
                        } elseif ($now >= $start && $now <= $end) { // Adjusted condition to include the end time
                            $status = "Ongoing";
                            $statusClass = "badge-ongoing";
                        } else {
                            $status = "Completed";
                            $statusClass = "badge-completed";
                        }
                    ?>
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <div class="card-title-wrapper">
                                        <h3 class="card-title"><?php echo htmlspecialchars($quiz['title']); ?></h3>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                                    </div>
                                    <div class="card-description">Subject: <?php echo htmlspecialchars($subject['name'] ?? 'N/A'); ?></div>
                                </div>
                                <button class="btn btn-ghost btn-sm toggle-details" data-id="<?php echo $quiz['id']; ?>">
                                    <span class="toggle-text">Details</span>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </button>
                            </div>
                            <div id="details-<?php echo $quiz['id']; ?>" class="collapsible-content">
                                <div class="card-content">
                                    <div class="quiz-details">
                                        <div>
                                            <p class="quiz-detail-item">Year: <?php echo $quiz['year']; ?></p>
                                            <p class="quiz-detail-item">Section: <?php echo $quiz['section']; ?></p>
                                            <p class="quiz-detail-item">Branch: <?php echo $quiz['branch']; ?></p>
                                            <p class="quiz-detail-item">Duration: <?php echo $quiz['duration']; ?> minutes</p>
                                        </div>
                                        <div>
                                            <p class="quiz-detail-item">Start Date: <?php echo $quiz['quiz_start_datetime']; ?></p>
                                            <p class="quiz-detail-item">End Date: <?php echo $quiz['quiz_end_datetime']; ?></p>
                                            <p class="quiz-detail-item">Result Publish Date: <?php echo $quiz['result_publish_date']; ?></p>
                                        </div>
                                    </div>
                                    <div class="quiz-actions">
                                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-outline btn-sm">
                                            <i class="fas fa-edit"></i>
                                            Edit Quiz
                                        </a>
                                        <a href="view_question.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-outline btn-sm">
                                            <i class="fas fa-list"></i>
                                            Manage Questions
                                        </a>
                                        <button class="btn btn-destructive btn-sm" onclick="confirmDeleteQuiz(<?php echo $quiz['id']; ?>, '<?php echo htmlspecialchars($quiz['title']); ?>')">
                                            <i class="fas fa-trash"></i>
                                            Delete Test
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="card">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <p class="empty-state-text">No tests found. You can create one using the "Create New Test" option.</p>
                            <a href="create_quiz.php" class="btn">Create New Test</a>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Subjects Section -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Your Subjects</h2>
                    <a href="create_subject.php" class="btn">
                        <i class="fas fa-plus-circle"></i>
                        Create New Subject
                    </a>
                </div>

                <?php if (mysqli_num_rows($subjects_query) > 0) { ?>
                    <div class="subject-grid">
                        <?php while ($subject = mysqli_fetch_assoc($subjects_query)) { ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo htmlspecialchars($subject['name']); ?></h3>
                                </div>
                                <div class="card-content">
                                    <div class="quiz-actions">
                                        <button class="btn btn-destructive btn-sm" onclick="confirmDeleteSubject(<?php echo $subject['id']; ?>, '<?php echo htmlspecialchars($subject['name']); ?>')">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="card">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="empty-state-text">No subjects found. Try adding a new subject.</p>
                            <a href="create_subject.php" class="btn">Create New Subject</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Quiz Confirmation Modal -->
<div id="delete-quiz-modal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Are you sure?</h3>
        </div>
        <div class="modal-body">
            <p class="modal-description">This will permanently delete the test "<span id="quiz-name"></span>" and all associated questions and results.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('delete-quiz-modal')">Cancel</button>
            <a href="#" id="confirm-delete-quiz" class="btn btn-destructive">Delete</a>
        </div>
    </div>
</div>

<!-- Delete Subject Confirmation Modal -->
<div id="delete-subject-modal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Are you sure?</h3>
        </div>
        <div class="modal-body">
            <p class="modal-description">This will permanently delete the subject "<span id="subject-name"></span>". This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('delete-subject-modal')">Cancel</button>
            <a href="#" id="confirm-delete-subject" class="btn btn-destructive">Delete</a>
        </div>
    </div>
</div>

<script>
    // Toggle sidebar on mobile
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }

    // Toggle quiz details
    const toggleButtons = document.querySelectorAll('.toggle-details');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const quizId = this.getAttribute('data-id');
            const detailsElement = document.getElementById(`details-${quizId}`);
            const toggleText = this.querySelector('.toggle-text');
            const toggleIcon = this.querySelector('.toggle-icon');
            
            if (detailsElement.classList.contains('open')) {
                detailsElement.classList.remove('open');
                toggleText.textContent = 'Details';
                toggleIcon.classList.remove('fa-chevron-up');
                toggleIcon.classList.add('fa-chevron-down');
            } else {
                detailsElement.classList.add('open');
                toggleText.textContent = 'Hide Details';
                toggleIcon.classList.remove('fa-chevron-down');
                toggleIcon.classList.add('fa-chevron-up');
            }
        });
    });

    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('open');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('open');
    }

    // Delete quiz confirmation
    function confirmDeleteQuiz(quizId, quizName) {
        document.getElementById('quiz-name').textContent = quizName;
        document.getElementById('confirm-delete-quiz').href = `?delete_quiz=${quizId}`;
        openModal('delete-quiz-modal');
    }

    // Delete subject confirmation
    function confirmDeleteSubject(subjectId, subjectName) {
        document.getElementById('subject-name').textContent = subjectName;
        document.getElementById('confirm-delete-subject').href = `?delete_subject=${subjectId}`;
        openModal('delete-subject-modal');
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal-backdrop');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('open');
            }
        });
    });
</script>

</body>
</html>