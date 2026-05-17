<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$gradeController = new GradeController();
$studentController = new StudentController();
$subjectController = new SubjectController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['teacher_id'] = $teacherId;

    if ($gradeController->create($_POST)) {
        $_SESSION['success'] = 'Grade added.';
        redirectTo('teacher/grades.php');
    }
}

$grades = $gradeController->byTeacher($teacherId);
$students = $studentController->all();
$subjects = $subjectController->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Grades</h1>

    <div class="card">
        <h3>Add Grade</h3>

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

            <label>Grade</label>
            <input type="number" step="0.01" min="<?= GRADE_MIN ?>" max="<?= GRADE_MAX ?>" name="grade" required>

            <button>Add Grade</button>
        </form>
    </div>

    <div class="card">
        <h3>My Grade Records</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>

            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= e($grade['id']) ?></td>
                    <td><?= e($grade['student_name']) ?></td>
                    <td><?= e($grade['subject_name']) ?></td>
                    <td><?= e($grade['grade']) ?></td>
                    <td><?= e($grade['remarks']) ?></td>
                    <td>
                        <a class="btn btn-warning" href="grade_edit.php?id=<?= e($grade['id']) ?>">Edit</a>
                        <a class="btn btn-danger confirm-delete" href="grade_delete.php?id=<?= e($grade['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($grades)): ?>
                <tr>
                    <td colspan="6">No grades found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>