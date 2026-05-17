<?php
require_once __DIR__ . '/../../config/db.php';

requireRole(ROLE_ADMIN);

$tables = ['students', 'teachers', 'subjects', 'grades', 'attendance', 'announcements'];
$trash = [];

try {
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE record_status = ?");
        $stmt->execute([RECORD_DELETED]);
        $trash[$table] = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Trash</h1>

    <?php foreach ($trash as $table => $items): ?>
        <div class="card">
            <h3><?= e(ucfirst($table)) ?></h3>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Details</th>
                    <th>Restore</th>
                </tr>

                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['id']) ?></td>
                        <td><?= e(json_encode($item)) ?></td>
                        <td>
                            <a class="btn" href="restore.php?table=<?= e($table) ?>&id=<?= e($item['id']) ?>">
                                Restore
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="3">No deleted records.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    <?php endforeach; ?>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>