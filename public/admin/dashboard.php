<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

requireRole(ROLE_ADMIN);

$students = $pdo->query("SELECT COUNT(*) FROM students WHERE record_status = 'active'")->fetchColumn();
$teachers = $pdo->query("SELECT COUNT(*) FROM teachers WHERE record_status = 'active'")->fetchColumn();
$subjects = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$grades = $pdo->query("SELECT COUNT(*) FROM grades")->fetchColumn();
$attendance = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
$announcements = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Admin Dashboard</h1>

    <div class="grid">
        <div class="card"><h3>Students</h3><p><?= $students ?></p></div>
        <div class="card"><h3>Teachers</h3><p><?= $teachers ?></p></div>
        <div class="card"><h3>Subjects</h3><p><?= $subjects ?></p></div>
        <div class="card"><h3>Grades</h3><p><?= $grades ?></p></div>
        <div class="card"><h3>Attendance</h3><p><?= $attendance ?></p></div>
        <div class="card"><h3>Announcements</h3><p><?= $announcements ?></p></div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>