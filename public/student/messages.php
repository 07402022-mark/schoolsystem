<?php
require_once __DIR__ . '/../../app/controllers/PasswordRequestController.php';

requireRole(ROLE_STUDENT);

$studentId = $_GET['student_id'] ?? ($_GET['student_number'] ?? '');

$controller = new PasswordRequestController();
$messages = [];

if ($studentId) {
    $messages = $controller->byStudentId($studentId);
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1>Messages</h1>

    <div class="card">
        <form method="GET">
            <label>Enter Student ID</label>
            <input name="student_id" value="<?= e($studentId) ?>" required>
            <button type="submit">Check Messages</button>
        </form>
    </div>

    <div class="card">
        <table>
            <tr>
                <th>Status</th>
                <th>Admin Reply</th>
                <th>New Password</th>
                <th>Date</th>
            </tr>

            <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?= e($message['status'] ?? '') ?></td>
                    <td><?= e($message['admin_reply'] ?? '') ?></td>
                    <td><?= e($message['new_password_text'] ?? '') ?></td>
                    <td><?= e($message['created_at'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($messages) && $studentId): ?>
                <tr>
                    <td colspan="4">No messages found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>