<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: teachers.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM teachers
    WHERE id = ?
");

$stmt->execute([$id]);

$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    die("Teacher not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $teacher_number = trim($_POST['teacher_number']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);

    $stmt = $pdo->prepare("
        UPDATE teachers
        SET teacher_number = ?,
            name = ?,
            email = ?,
            department = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $teacher_number,
        $name,
        $email,
        $department,
        $id
    ]);

    header("Location: teachers.php");
    exit;
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="content">
    <h1>Edit Teacher</h1>

    <div class="card">
        <form method="POST">

            <input type="text"
                   name="teacher_number"
                   value="<?= htmlspecialchars($teacher['teacher_number']) ?>"
                   required>

            <input type="text"
                   name="name"
                   value="<?= htmlspecialchars($teacher['name']) ?>"
                   required>

            <input type="email"
                   name="email"
                   value="<?= htmlspecialchars($teacher['email']) ?>"
                   required>

            <input type="text"
                   name="department"
                   value="<?= htmlspecialchars($teacher['department']) ?>"
                   required>

            <button type="submit" class="btn">
                Update Teacher
            </button>

        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>