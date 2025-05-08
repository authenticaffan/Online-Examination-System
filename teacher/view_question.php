<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$message = "";

if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    $quiz_result = mysqli_query($conn, "SELECT * FROM quizzes WHERE id = '$quiz_id' AND teacher_id = '$teacher_id'");
    $quiz = mysqli_fetch_assoc($quiz_result);

    if (!$quiz) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete'])) {
    $question_id = $_GET['delete'];
    $delete_query = "DELETE FROM questions WHERE id = '$question_id' AND quiz_id = '$quiz_id'";

    if (mysqli_query($conn, $delete_query)) {
        $message = "✅ Question deleted successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

$questions_result = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id = '$quiz_id'");
$total_questions = mysqli_num_rows($questions_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #ff4757;
            font-size: 16px;
        }

        .add-question-btn {
            text-align: right;
            margin-bottom: 10px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            text-decoration: none;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-add {
            background-color: #4CAF50;
            font-weight: bold;
        }

        .btn-add:hover {
            background-color: #45a049;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-bar:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 24px;
            }

            table {
                font-size: 14px;
            }

            .btn {
                display: block;
                margin: 5px 0;
            }

            .add-question-btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Questions for Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="add-question-btn">
        <a href="add_questions.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-add">Add New Question</a>
    </div>

    <input type="text" class="search-bar" id="searchQuestions" placeholder="Search questions..." onkeyup="searchQuestions()">

    <?php if ($total_questions > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="questionsTableBody">
                    <?php
                    $index = 1;
                    while ($row = mysqli_fetch_assoc($questions_result)) {
                        echo "<tr>
                                <td>{$index}</td>
                                <td>" . htmlspecialchars($row['question']) . "</td>
                                <td>
                                    <a href='edit_question.php?id={$row['id']}&quiz_id={$quiz_id}' class='btn'>Edit</a>
                                    <a href='?delete={$row['id']}&quiz_id={$quiz_id}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this question?\")'>Delete</a>
                                </td>
                            </tr>";
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="message" style="text-align: center;">❌ No questions present, insert a new question.</p>
    <?php endif; ?>
</div>

<script>
    function searchQuestions() {
        let input = document.getElementById("searchQuestions");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("questionsTableBody");
        let tr = table.getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().includes(filter) ? "" : "none";
            }
        }
    }
</script>

</body>
</html>