<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$gradeController = new GradeController();
$studentController = new StudentController();
$subjectController = new SubjectController();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('teacher/grades.php');
}

$grade = $gradeController->find($id);
$students = $studentController->all();
$subjects = $subjectController->byTeacher($teacherId);

if (!$grade || $grade['teacher_id'] != $teacherId) {
    $_SESSION['error'] = 'Grade not found.';
    redirectTo('teacher/grades.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['teacher_id'] = $teacherId;

    if ($gradeController->update($id, $_POST)) {
        $_SESSION['success'] = 'Grade updated.';
        redirectTo('teacher/grades.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Edit Grade</h1>

    <div class="card">
        <form method="POST">
            <label>Student</label>
            <select name="student_id" required>
                <?php foreach ($students as $student): ?>
                    <option value="<?= e($student['id']) ?>" <?= $grade['student_id'] == $student['id'] ? 'selected' : '' ?>>
                        <?= e($student['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Subject</label>
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>" <?= $grade['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['code']) ?> - <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Grade</label>
            <input type="number" step="0.01" min="<?= GRADE_MIN ?>" max="<?= GRADE_MAX ?>" name="grade" value="<?= e($grade['grade']) ?>" required>

            <button>Update</button>
            <a class="btn" href="grades.php">Cancel</a>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>