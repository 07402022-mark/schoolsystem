<?php
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$attendanceController = new AttendanceController();
$studentController = new StudentController();
$subjectController = new SubjectController();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('teacher/attendance.php');
}

$row = $attendanceController->find($id);
$students = $studentController->all();
$subjects = $subjectController->byTeacher($teacherId);

if (!$row || $row['teacher_id'] != $teacherId) {
    $_SESSION['error'] = 'Attendance not found.';
    redirectTo('teacher/attendance.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['teacher_id'] = $teacherId;

    if ($attendanceController->update($id, $_POST)) {
        $_SESSION['success'] = 'Attendance updated.';
        redirectTo('teacher/attendance.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Edit Attendance</h1>

    <div class="card">
        <form method="POST">
            <label>Student</label>
            <select name="student_id" required>
                <?php foreach ($students as $student): ?>
                    <option value="<?= e($student['id']) ?>" <?= $row['student_id'] == $student['id'] ? 'selected' : '' ?>>
                        <?= e($student['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Subject</label>
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>" <?= $row['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['code']) ?> - <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Date</label>
            <input type="date" name="attendance_date" value="<?= e($row['attendance_date']) ?>" required>

            <label>Status</label>
            <select name="status" required>
                <option value="<?= ATT_PRESENT ?>" <?= $row['status'] === ATT_PRESENT ? 'selected' : '' ?>>Present</option>
                <option value="<?= ATT_ABSENT ?>" <?= $row['status'] === ATT_ABSENT ? 'selected' : '' ?>>Absent</option>
                <option value="<?= ATT_LATE ?>" <?= $row['status'] === ATT_LATE ? 'selected' : '' ?>>Late</option>
            </select>

            <button>Update</button>
            <a class="btn" href="attendance.php">Cancel</a>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>