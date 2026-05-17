<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$gradeController = new GradeController();
$attendanceController = new AttendanceController();

$grades = $gradeController->byTeacher($teacherId);
$attendance = $attendanceController->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Class Records</h1>

    <p>
        <a class="btn" href="export_class_record.php">Export CSV</a>
        <button onclick="printPage()">Print</button>
    </p>

    <div class="card">
        <h3>Grades</h3>

        <table>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>

            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= e($grade['student_name']) ?></td>
                    <td><?= e($grade['subject_name']) ?></td>
                    <td><?= e($grade['grade']) ?></td>
                    <td><?= e($grade['remarks']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card">
        <h3>Attendance</h3>

        <table>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php foreach ($attendance as $row): ?>
                <tr>
                    <td><?= e($row['student_name']) ?></td>
                    <td><?= e($row['subject_name'] ?? 'None') ?></td>
                    <td><?= e($row['attendance_date']) ?></td>
                    <td><?= e($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>