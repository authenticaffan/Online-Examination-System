<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$quizzes = mysqli_query($conn, "SELECT * FROM quizzes WHERE teacher_id = '$teacher_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .quiz-selector {
            text-align: center;
            margin-bottom: 30px;
        }

        .quiz-selector select {
            padding: 10px;
            width: 250px;
            border-radius: 5px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        .quiz-card {
            margin-bottom: 40px;
        }

        .quiz-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            border-left: 5px solid #007bff;
            padding-left: 10px;
            color: #007bff;
        }

        .download-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .download-btn:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .no-results {
            background-color: #ffc107;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .back-to-dashboard {
            display: block;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            margin-top: 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .back-to-dashboard:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 22px;
            }

            table, th, td {
                font-size: 14px;
            }

            .quiz-card h3 {
                font-size: 18px;
            }

            .back-to-dashboard {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View Results</h2>

    <!-- Quiz Selector -->
    <div class="quiz-selector">
        <label for="quizSelector" style="font-weight: 500; margin-right: 10px;">Select Test:</label>
        <select id="quizSelector">
            <option value="">-- Choose a test --</option>
            <?php 
            mysqli_data_seek($quizzes, 0);
            while ($quiz = mysqli_fetch_assoc($quizzes)) { ?>
                <option value="quiz-<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Quiz Results -->
    <?php 
    mysqli_data_seek($quizzes, 0);
    while ($quiz = mysqli_fetch_assoc($quizzes)) { 
        $quiz_id = $quiz['id'];
        $results = mysqli_query($conn, "
            SELECT r.student_id, r.score, s.name, s.register_number 
            FROM results r 
            JOIN students s ON r.student_id = s.id
            WHERE r.quiz_id = '$quiz_id'
        ");
    ?>
        <div class="quiz-card" id="quiz-<?php echo $quiz_id; ?>" style="display: none;">
            <h3>Test: <?php echo htmlspecialchars($quiz['title']); ?></h3>
            <button class="download-btn" data-id="quiz-<?php echo $quiz_id; ?>">Download PDF</button>

            <?php if (mysqli_num_rows($results) > 0) { ?>
                <table class="display">
                    <thead>
                        <tr>
                            <th>Register Number</th>
                            <th>Student Name</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($results)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['register_number']); ?></td>
                                <td><?php echo htmlspecialchars($result['name']); ?></td>
                                <td><?php echo htmlspecialchars($result['score']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="no-results">No students have taken this test yet.</div>
            <?php } ?>
        </div>
    <?php } ?>

    <a href="dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    $(document).ready(function () {
        $('table.display').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 20]
        });

        $('#quizSelector').on('change', function () {
            const selectedId = $(this).val();
            $('.quiz-card').hide();
            if (selectedId) {
                $('#' + selectedId).fadeIn();
            }
        });

        $('.download-btn').on('click', function () {
            const quizCardId = $(this).data('id');
            const quizCard = document.getElementById(quizCardId);

            html2canvas(quizCard, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                const pdfWidth = 210;
                const pageHeight = 297;
                const imgProps = pdf.getImageProperties(imgData);
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                let heightLeft = pdfHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - pdfHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                    heightLeft -= pageHeight;
                }

                const quizTitle = $(quizCard).find('h3').text().trim().replace(/[^a-zA-Z0-9]/g, "_");
                pdf.save(quizTitle + '_results.pdf');
            });
        });
    });
</script>

</body>
</html>
