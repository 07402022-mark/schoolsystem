<?php
require_once __DIR__ . '/../../app/controllers/PasswordRequestController.php';

requireRole(ROLE_ADMIN);

$controller = new PasswordRequestController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->reply($_POST['id'], $_POST['admin_reply'], $_POST['new_password'])) {
        $_SESSION['success'] = 'Password request replied and password updated.';
        redirectTo('admin/password_requests.php');
    }
}

$requests = $controller->all();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Password Requests</h1>

    <div class="card">
        <table>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Email</th>
                <th>Message</th>
                <th>Status</th>
                <th>Reply</th>
            </tr>

            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= e($request['id']) ?></td>
                    <td><?= e($request['student_id']) ?></td>
                    <td><?= e($request['email']) ?></td>
                    <td><?= e($request['message']) ?></td>
                    <td><?= e($request['status']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= e($request['id']) ?>">

                            <label>Admin Reply</label>
                            <textarea name="admin_reply" required><?= e($request['admin_reply'] ?? '') ?></textarea>

                            <label>New Password</label>
                            <input type="password" name="new_password" required>

                            <button type="submit">Update Password</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>