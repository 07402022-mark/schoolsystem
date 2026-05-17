<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

requireRole(ROLE_ADMIN);

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: teacher_subjects.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->execute([$id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    die("Subject not found.");
}

$teachers = $pdo->query("
    SELECT id, name
    FROM teachers
    WHERE record_status = 'active'
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $teacher_id = $_POST['teacher_id'];

    $stmt = $pdo->prepare("
        UPDATE subjects
        SET code = ?, name = ?, course = ?, year_level = ?, teacher_id = ?
        WHERE id = ?
    ");

    $stmt->execute([$code, $name, $course, $year_level, $teacher_id, $id]);

    header("Location: teacher_subjects.php");
    exit;
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Edit Subject</h1>

    <div class="card">
        <form method="POST" style="display:grid; gap:10px; max-width:500px;">
            <input type="text" name="code" value="<?= htmlspecialchars($subject['code']) ?>" required>
            <input type="text" name="name" value="<?= htmlspecialchars($subject['name']) ?>" required>
            <input type="text" name="course" value="<?= htmlspecialchars($subject['course']) ?>" required>
            <input type="text" name="year_level" value="<?= htmlspecialchars($subject['year_level']) ?>" required>

            <select name="teacher_id" required>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= htmlspecialchars($teacher['id']) ?>"
                        <?= $teacher['id'] == $subject['teacher_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($teacher['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn">Update Subject</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>