<?php
require_once __DIR__ . '/../../app/controllers/Auth.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

Auth::requireRole(Constant::ROLE_STUDENT);

$user = Auth::user();
$db = Database::connect();

$markSeen = $db->prepare("
    UPDATE password_requests
    SET student_seen = 1
    WHERE email = :email AND status = 'done'
");
$markSeen->execute([
    'email' => $user['email']
]);

$stmt = $db->prepare("
    SELECT * FROM password_requests
    WHERE email = :email AND status = 'done'
    ORDER BY replied_at DESC
");
$stmt->execute([
    'email' => $user['email']
]);

$messages = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="layout">
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<main class="content">
    <h1>Admin Messages</h1>

    <div class="card">
        <?php foreach ($messages as $m): ?>
            <div class="card">
                <p><strong>Admin Message:</strong></p>
                <p><?= nl2br(htmlspecialchars($m['admin_reply'] ?? '')) ?></p>

                <p><strong>Your New Password:</strong></p>
                <p><?= htmlspecialchars($m['new_password_text'] ?? '') ?></p>

                <p><strong>Date:</strong> <?= htmlspecialchars($m['replied_at'] ?? '') ?></p>
            </div>
        <?php endforeach; ?>

        <?php if (count($messages) === 0): ?>
            <p>No admin messages yet.</p>
        <?php endif; ?>
    </div>
</main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>