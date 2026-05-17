<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';
require_once __DIR__ . '/../../app/controllers/SubjectController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$announcementController = new AnnouncementController();
$subjectController = new SubjectController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['author_role'] = ROLE_TEACHER;
    $_POST['author_id'] = $teacherId;
    $_POST['type'] = ANNOUNCEMENT_CLASS;

    if ($announcementController->create($_POST)) {
        $_SESSION['success'] = 'Announcement posted.';
        redirectTo('teacher/announcements.php');
    }
}

$announcements = $announcementController->byTeacher($teacherId);
$subjects = $subjectController->byTeacher($teacherId);
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/teacher_sidebar.php'; ?>

<section class="content">
    <h1>Announcements</h1>

    <div class="card">
        <h3>Post Class Announcement</h3>

        <form method="POST">
            <label>Title</label>
            <input name="title" required>

            <label>Message / Activity</label>
            <textarea name="message" required></textarea>

            <label>Subject</label>
            <select name="subject_id" required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= e($subject['id']) ?>">
                        <?= e($subject['code']) ?> - <?= e($subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Publish At</label>
            <input type="datetime-local" name="publish_at">

            <button>Post</button>
        </form>
    </div>

    <div class="card">
        <h3>My Announcements</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Publish At</th>
                <th>Action</th>
            </tr>

            <?php foreach ($announcements as $announcement): ?>
                <tr>
                    <td><?= e($announcement['id']) ?></td>
                    <td><?= e($announcement['title']) ?></td>
                    <td><?= e($announcement['type']) ?></td>
                    <td><?= e($announcement['publish_at']) ?></td>
                    <td>
                        <a class="btn btn-warning" href="announcement_edit.php?id=<?= e($announcement['id']) ?>">Edit</a>
                        <a class="btn btn-danger confirm-delete" href="announcement_delete.php?id=<?= e($announcement['id']) ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($announcements)): ?>
                <tr>
                    <td colspan="5">No announcements found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>