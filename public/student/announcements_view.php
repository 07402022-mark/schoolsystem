<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';

requireRole(ROLE_STUDENT);

$controller = new AnnouncementController();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirectTo('student/announcements.php');
}

$announcement = $controller->find($id);

if (!$announcement) {
    $_SESSION['error'] = 'Announcement not found.';
    redirectTo('student/announcements.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->submitAnswer($announcement['id'], currentUser()['student_id'], $_POST['answer'])) {
        $_SESSION['success'] = 'Answer submitted.';
        redirectTo('student/announcements.php');
    }
}
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/student_sidebar.php'; ?>

<section class="content">
    <h1><?= e($announcement['title']) ?></h1>

    <div class="card">
        <p><strong>Type:</strong> <?= e($announcement['type']) ?></p>
        <p><strong>Publish At:</strong> <?= e($announcement['publish_at']) ?></p>

        <hr>

        <p><?= nl2br(e($announcement['message'])) ?></p>
    </div>

    <?php if ($announcement['type'] === ANNOUNCEMENT_CLASS): ?>
        <div class="card">
            <h3>Submit Answer</h3>

            <form method="POST">
                <label>Your Answer</label>
                <textarea name="answer" required></textarea>

                <button>Submit</button>
            </form>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>