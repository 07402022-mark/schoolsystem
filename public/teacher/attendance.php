<?php
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$attendanceController = new AttendanceController();
$studentController = new StudentController();
$subjectController = new SubjectController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['teacher_id'] = $teacherId;

    if ($attendanceController->create($_POST)) {
        $_SESSION['success'] = 'Attendance added.';
        redirectTo('teacher/attendance.php');
    }
}

$attendance = $attendanceController->byTeacher($teacherId);
$students = $studentController->all();
$subjects = $subjectController->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Attendance</h1>

    <div class="card">
        <h3>Add Attendance</h3>

        <form method="POST">
            <label>Student</label>
            <select name="student_id" required>
                <?php foreach ($students as $student): ?>
                    <option value="<?= e($student['id']) ?>"><?= e($student['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Subject</label>
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>">
                        <?= e($subject['code']) ?> - <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date</label>
            <input type="date" name="attendance_date" required>

            <label>Status</label>
            <select name="status" required>
                <option value="<?= ATT_PRESENT ?>">Present</option>
                <option value="<?= ATT_ABSENT ?>">Absent</option>
                <option value="<?= ATT_LATE ?>">Late</option>
            </select>

            <button>Add Attendance</button>
        </form>
    </div>

    <div class="card">
        <h3>My Attendance Records</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($attendance as $row): ?>
                <tr>
                    <td><?= e($row['id']) ?></td>
                    <td><?= e($row['student_name']) ?></td>
                    <td><?= e($row['subject_name'] ?? 'None') ?></td>
                    <td><?= e($row['attendance_date']) ?></td>
                    <td><?= e($row['status']) ?></td>
                    <td>
                        <a class="btn btn-warning" href="attendance_edit.php?id=<?= e($row['id']) ?>">Edit</a>
                        <a class="btn btn-danger confirm-delete" href="attendance_delete.php?id=<?= e($row['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($attendance)): ?>
                <tr>
                    <td colspan="6">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>