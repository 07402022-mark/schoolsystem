<?php
require_once __DIR__ . '/../../app/controllers/SubjectController.php';
require_once __DIR__ . '/../../app/controllers/GradeController.php';
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$subjectController = new SubjectController();
$gradeController = new GradeController();
$attendanceController = new AttendanceController();

$subjects = $subjectController->byTeacher($teacherId);
$grades = $gradeController->byTeacher($teacherId);
$attendance = $attendanceController->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Teacher Dashboard</h1>

    <div class="grid">

        <div class="card">
            <h3>My Subjects</h3>
            <p><?= e(count($subjects)) ?></p>
        </div>

        <div class="card">
            <h3>Student Grades</h3>
            <p><?= e(count($grades)) ?></p>
        </div>

        <div class="card">
            <h3>Attendance Records</h3>
            <p><?= e(count($attendance)) ?></p>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>   