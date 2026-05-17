<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';

requireRole(ROLE_STUDENT);

$controller = new AnnouncementController();
$announcements = $controller->all();

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/student_sidebar.php';
?>

<section class="content">
    <h1>Announcements</h1>

    <div class="card">
        <div class="table-search">
            <input type="text" id="tableSearch" placeholder="Search announcements...">
        </div>

        <table id="dataTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Publish At</th>
                    <th>View</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($announcements as $announcement): ?>
                    <tr>
                        <td><?= e($announcement['title']) ?></td>
                        <td><?= e($announcement['message']) ?></td>
                        <td><?= e($announcement['type']) ?></td>
                        <td><?= e($announcement['subject_name'] ?? 'None') ?></td>
                        <td><?= e($announcement['publish_at']) ?></td>
                        <td>
                            <a class="btn" href="announcements_view.php?id=<?= e($announcement['id']) ?>">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($announcements)): ?>
                    <tr>
                        <td colspan="6">No announcements available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>