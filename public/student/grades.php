<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';

requireRole(ROLE_STUDENT);

$studentId = currentUser()['student_id'];

$controller = new GradeController();
$grades = $controller->byStudent($studentId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1>My Grades</h1>

    <div class="card">
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

            <?php if (empty($grades)): ?>
                <tr>
                    <td colspan="4">No grades found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>