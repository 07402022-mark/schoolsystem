<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

requireRole(ROLE_ADMIN);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $teacher_id = $_POST['teacher_id'];

    if ($code && $name && $course && $year_level && $teacher_id) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO subjects (code, name, teacher_id, year_level, course)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$code, $name, $teacher_id, $year_level, $course]);

            header("Location: teacher_subjects.php");
            exit;
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$teachers = $pdo->query("
    SELECT id, name
    FROM teachers
    WHERE record_status = 'active'
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$subjects = $pdo->query("
    SELECT 
        subjects.id,
        subjects.code,
        subjects.name,
        subjects.course,
        subjects.year_level,
        teachers.name AS teacher_name
    FROM subjects
    LEFT JOIN teachers ON subjects.teacher_id = teachers.id
    ORDER BY subjects.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Teacher Subjects</h1>

    <div class="card">
        <h3>Add Subject</h3>

        <?php if ($message): ?>
            <p style="color:red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" style="display:grid; gap:10px; max-width:500px;">
            <input type="text" name="code" placeholder="Subject Code" required>
            <input type="text" name="name" placeholder="Subject Name" required>
            <input type="text" name="course" placeholder="Course" required>
            <input type="text" name="year_level" placeholder="Year Level" required>

            <select name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= htmlspecialchars($teacher['id']) ?>">
                        <?= htmlspecialchars($teacher['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Add Subject</button>
        </form>
    </div>

    <div class="card">
        <h3>Subject List</h3>

        <table>
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Teacher</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="6">No subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['code']) ?></td>
                            <td><?= htmlspecialchars($subject['name']) ?></td>
                            <td><?= htmlspecialchars($subject['course']) ?></td>
                            <td><?= htmlspecialchars($subject['year_level']) ?></td>
                            <td><?= htmlspecialchars($subject['teacher_name'] ?? 'Unassigned') ?></td>
                            <td>
                                <a href="subject_edit.php?id=<?= $subject['id'] ?>">Edit</a> |
                                <a href="subject_delete.php?id=<?= $subject['id'] ?>"
                                   onclick="return confirm('Delete this subject?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>