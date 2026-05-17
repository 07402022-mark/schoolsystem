<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_ADMIN);

$controller = new AnnouncementController();
$subjectController = new SubjectController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['author_role'] = ROLE_ADMIN;
    $_POST['author_id'] = currentUser()['id'];

    if ($controller->create($_POST)) {
        $_SESSION['success'] = 'Announcement added.';
        redirectTo('admin/announcements.php');
    }
}

$announcements = $controller->all();
$subjects = $subjectController->all();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Announcements</h1>

    <div class="card">
        <h3>Add Announcement</h3>

        <form method="POST">
            <label>Title</label>
            <input name="title" required>

            <label>Message</label>
            <textarea name="message" required></textarea>

            <label>Type</label>
            <select name="type" required>
                <option value="<?= ANNOUNCEMENT_SCHOOL ?>">School</option>
                <option value="<?= ANNOUNCEMENT_CLASS ?>">Class</option>
            </select>

            <label>Subject</label>
            <select name="subject_id">
                <option value="">None</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>"><?= e($subject['code']) ?> - <?= e($subject['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Publish At</label>
            <input type="datetime-local" name="publish_at">

            <button>Add Announcement</button>
        </form>
    </div>

    <div class="card">
        <h3>Announcement List</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Publish At</th>
                <th>Action</th>
            </tr>

            <?php foreach ($announcements as $announcement): ?>
                <tr>
                    <td><?= e($announcement['id']) ?></td>
                    <td><?= e($announcement['title']) ?></td>
                    <td><?= e($announcement['type']) ?></td>
                    <td><?= e($announcement['subject_name'] ?? 'None') ?></td>
                    <td><?= e($announcement['publish_at']) ?></td>
                    <td>
                        <a class="btn btn-warning" href="announcement_edit.php?id=<?= e($announcement['id']) ?>">Edit</a>
                        <a class="btn btn-danger confirm-delete" href="announcement_delete.php?id=<?= e($announcement['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>