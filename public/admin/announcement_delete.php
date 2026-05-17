<?php
require_once __DIR__ . '/../../app/controllers/AnnouncementController.php';

requireRole(ROLE_ADMIN);

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new AnnouncementController();
    $controller->softDelete($id);
    $_SESSION['success'] = 'Announcement moved to trash.';
}

redirectTo('admin/announcements.php');