<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';

requireRole(ROLE_STUDENT);

$studentId = currentUser()['student_id'];

$gradeController = new GradeController();
$attendanceController = new AttendanceController();
$announcementController = new AnnouncementController();

$grades = $gradeController->byStudent($studentId);
$attendance = $attendanceController->byStudent($studentId);
$announcements = $announcementController->schoolAnnouncements();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1>Student Dashboard</h1>

    <div class="grid">
        <div class="card">
            <h3>Grades</h3>
            <p><?= e(count($grades)) ?></p>
        </div>

        <div class="card">
            <h3>Attendance</h3>
            <p><?= e(count($attendance)) ?></p>
        </div>

        <div class="card">
            <h3>Announcements</h3>
            <p><?= e(count($announcements)) ?></p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>