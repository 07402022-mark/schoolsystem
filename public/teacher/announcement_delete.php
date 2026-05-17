<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new AnnouncementController();
    $announcement = $controller->find($id);

    if ($announcement && $announcement['author_id'] == $teacherId && $announcement['author_role'] === ROLE_TEACHER) {
        $controller->softDelete($id);
        $_SESSION['success'] = 'Announcement deleted.';
    }
}

redirectTo('teacher/announcements.php');