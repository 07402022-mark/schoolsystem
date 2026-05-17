<?php
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$controller = new SubjectController();
$subjects = $controller->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>My Subjects</h1>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Subject</th>
                <th>Course</th>
                <th>Year Level</th>
            </tr>

            <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?= e($subject['id']) ?></td>
                    <td><?= e($subject['code']) ?></td>
                    <td><?= e($subject['name']) ?></td>
                    <td><?= e($subject['course']) ?></td>
                    <td><?= e($subject['year_level']) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($subjects)): ?>
                <tr>
                    <td colspan="5">No assigned subjects.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>