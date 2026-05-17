<?php
require_once __DIR__ . '/../../app/controllers/StudentController.php';

requireRole(ROLE_ADMIN);

$controller = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->create($_POST)) {
        $_SESSION['success'] = 'Student added.';
        redirectTo('admin/students.php');
    }
}

$students = $controller->all();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Students</h1>

    <div class="card">
        <h3>Add Student</h3>
<form method="POST">
    <label>Name</label>
    <input name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Course</label>
    <input name="course" required>

    <label>Year Level</label>
    <input name="year_level" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Add Student</button>
</form>
    </div>

    <div class="card">
        <h3>Student List</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Student No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Year</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= e($student['id']) ?></td>
                    <td><?= e($student['student_number']) ?></td>
                    <td><?= e($student['name']) ?></td>
                    <td><?= e($student['email']) ?></td>
                    <td><?= e($student['course']) ?></td>
                    <td><?= e($student['year_level']) ?></td>
                    <td><?= e($student['academic_status']) ?></td>
                    <td>
                        <a class="btn-warning btn" href="student_edit.php?id=<?= e($student['id']) ?>">Edit</a>
                        <a class="btn-danger btn confirm-delete" href="student_delete.php?id=<?= e($student['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>