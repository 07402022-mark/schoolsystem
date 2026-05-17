<?php
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';

requireRole(ROLE_STUDENT);

$studentId = currentUser()['student_id'];

$controller = new AttendanceController();
$attendance = $controller->byStudent($studentId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1>My Attendance</h1>

    <div class="card">
        <table>
            <tr>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php foreach ($attendance as $row): ?>
                <tr>
                    <td><?= e($row['subject_name'] ?? 'None') ?></td>
                    <td><?= e($row['attendance_date']) ?></td>
                    <td><?= e($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($attendance)): ?>
                <tr>
                    <td colspan="3">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>