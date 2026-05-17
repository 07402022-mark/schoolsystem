<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$announcementController = new AnnouncementController();
$subjectController = new SubjectController();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('teacher/announcements.php');
}

$announcement = $announcementController->find($id);
$subjects = $subjectController->byTeacher($teacherId);

if (!$announcement || $announcement['author_id'] != $teacherId || $announcement['author_role'] !== ROLE_TEACHER) {
    $_SESSION['error'] = 'Announcement not found.';
    redirectTo('teacher/announcements.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['type'] = ANNOUNCEMENT_CLASS;

    if ($announcementController->update($id, $_POST)) {
        $_SESSION['success'] = 'Announcement updated.';
        redirectTo('teacher/announcements.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Edit Announcement</h1>

    <div class="card">
        <form method="POST">
            <label>Title</label>
            <input name="title" value="<?= e($announcement['title']) ?>" required>

            <label>Message / Activity</label>
            <textarea name="message" required><?= e($announcement['message']) ?></textarea>

            <label>Subject</label>
            <select name="subject_id" required>
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