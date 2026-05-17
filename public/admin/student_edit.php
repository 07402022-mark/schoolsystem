<?php
require_once __DIR__ . '/../../app/controllers/StudentController.php';

requireRole(ROLE_ADMIN);

$controller = new StudentController();
$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('admin/students.php');
}

$student = $controller->find($id);

if (!$student) {
    $_SESSION['error'] = 'Student not found.';
    redirectTo('admin/students.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->update($id, $_POST)) {
        $_SESSION['success'] = 'Student updated.';
        redirectTo('admin/students.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Edit Student</h1>

    <div class="card">
        <form method="POST">
            <label>Student Number</label>
            <input name="student_number" value="<?= e($student['student_number']) ?>" required>

            <label>Name</label>
            <input name="name" value="<?= e($student['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= e($student['email']) ?>" required>

            <label>Course</label>
            <input name="course" value="<?= e($student['course']) ?>" required>

            <label>Year Level</label>
            <input name="year_level" value="<?= e($student['year_level']) ?>" required>

            <label>Academic Status</label>
            <select name="academic_status">
                <option value="<?= ACADEMIC_ACTIVE ?>" <?= $student['academic_status'] === ACADEMIC_ACTIVE ? 'selected' : '' ?>>Active</option>
                <option value="<?= ACADEMIC_PASSED ?>" <?= $student['academic_status'] === ACADEMIC_PASSED ? 'selected' : '' ?>>Passed</option>
                <option value="<?= ACADEMIC_FAILED ?>" <?= $student['academic_status'] === ACADEMIC_FAILED ? 'selected' : '' ?>>Failed</option>
                <option value="<?= ACADEMIC_DROPPED ?>" <?= $student['academic_status'] === ACADEMIC_DROPPED ? 'selected' : '' ?>>Dropped</option>
            </select>

            <button>Update</button>
            <a class="btn" href="students.php">Cancel</a>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>