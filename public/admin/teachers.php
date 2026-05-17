<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_number = trim($_POST['teacher_number']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $password = trim($_POST['password']);

    if ($teacher_number && $name && $email && $department && $password) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                INSERT INTO teachers 
                (teacher_number, name, email, department, record_status)
                VALUES (?, ?, ?, ?, 'active')
            ");

            $stmt->execute([
                $teacher_number,
                $name,
                $email,
                $department
            ]);

            $teacher_id = $pdo->lastInsertId();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users
                (name, email, password, role, status, teacher_id)
                VALUES (?, ?, ?, 'teacher', 'active', ?)
            ");

            $stmt->execute([
                $name,
                $email,
                $hashed_password,
                $teacher_id
            ]);

            $pdo->commit();

            header("Location: teachers.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$stmt = $pdo->prepare("
    SELECT id, teacher_number, name, email, department
    FROM teachers
    WHERE record_status = 'active'
    ORDER BY id DESC
");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<div class="content">
    <h1>Teachers</h1>

    <div class="card">
        <h3>Add Teacher</h3>

        <?php if ($message): ?>
            <p style="color:red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" style="display:grid; gap:10px; max-width:500px;">
            <input type="text" name="teacher_number" placeholder="Teacher Number" required>
            <input type="text" name="name" placeholder="Teacher Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn">Add Teacher</button>
        </form>
    </div>

    <div class="card">
        <h3>Teacher List</h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Teacher No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="6">No teachers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= htmlspecialchars($teacher['id']) ?></td>
                            <td><?= htmlspecialchars($teacher['teacher_number']) ?></td>
                            <td><?= htmlspecialchars($teacher['name']) ?></td>
                            <td><?= htmlspecialchars($teacher['email']) ?></td>
                            <td><?= htmlspecialchars($teacher['department']) ?></td>
                            <td>
                                <a href="teacher_edit.php?id=<?= $teacher['id'] ?>">Edit</a> |
                                <a href="teacher_delete.php?id=<?= $teacher['id'] ?>"
                                   onclick="return confirm('Delete this teacher?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?> 