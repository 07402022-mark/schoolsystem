<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';

requireRole(ROLE_STUDENT);

$studentId = currentUser()['student_id'];

$controller = new GradeController();
$grades = $controller->byStudent($studentId);

$total = ZERO;
$count = ZERO;

foreach ($grades as $grade) {
    $total += (float)$grade['grade'];
    $count++;
}

$average = $count > ZERO ? $total / $count : ZERO;
$finalRemarks = $average >= GRADE_PASSING ? 'Passed' : 'Failed';
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1>Report Card</h1>

    <p>
        <button onclick="printPage()">Print Report Card</button>
    </p>

    <div class="card">
        <h3><?= e(currentUser()['name']) ?></h3>

        <table>
            <tr>
                <th>Subject Code</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>

            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= e($grade['code']) ?></td>
                    <td><?= e($grade['subject_name']) ?></td>
                    <td><?= e($grade['grade']) ?></td>
                    <td><?= e($grade['remarks']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Average: <?= e(number_format($average, 2)) ?></h3>
        <h3>Final Remarks: <?= e($finalRemarks) ?></h3>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>