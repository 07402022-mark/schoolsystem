<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_ADMIN);

$controller = new AnnouncementController();
$subjectController = new SubjectController();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('admin/announcements.php');
}

$announcement = $controller->find($id);
$subjects = $subjectController->all();

if (!$announcement) {
    $_SESSION['error'] = 'Announcement not found.';
    redirectTo('admin/announcements.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->update($id, $_POST)) {
        $_SESSION['success'] = 'Announcement updated.';
        redirectTo('admin/announcements.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Edit Announcement</h1>

    <div class="card">
        <form method="POST">
            <label>Title</label>
            <input name="title" value="<?= e($announcement['title']) ?>" required>

            <label>Message</label>
            <textarea name="message" required><?= e($announcement['message']) ?></textarea>

            <label>Type</label>
            <select name="type" required>
                <option value="<?= ANNOUNCEMENT_SCHOOL ?>" <?= $announcement['type'] === ANNOUNCEMENT_SCHOOL ? 'selected' : '' ?>>School</option>
                <option value="<?= ANNOUNCEMENT_CLASS ?>" <?= $announcement['type'] === ANNOUNCEMENT_CLASS ? 'selected' : '' ?>>Class</option>
            </select>

            <label>Subject</label>
            <select name="subject_id">
                <option value="">None</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>" <?= $announcement['subject_id'] == $subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['code']) ?> - <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Publish At</label>
            <input type="datetime-local" name="publish_at" value="<?= e(str_replace(' ', 'T', $announcement['publish_at'])) ?>">

            <button>Update</button>
            <a class="btn" href="announcements.php">Cancel</a>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>