<?php
require_once __DIR__ . '/../../app/controllers/ActivityLogController.php';

requireRole(ROLE_ADMIN);

$controller = new ActivityLogController();
$logs = $controller->all();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Activity Logs</h1>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Role</th>
                <th>Action</th>
                <th>Details</th>
                <th>Date</th>
            </tr>

            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= e($log['id']) ?></td>
                    <td><?= e($log['user_id']) ?></td>
                    <td><?= e($log['role']) ?></td>
                    <td><?= e($log['action']) ?></td>
                    <td><?= e($log['details']) ?></td>
                    <td><?= e($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>