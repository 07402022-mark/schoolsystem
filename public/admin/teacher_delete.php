<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';
require_once __DIR__ . '/../../app/controllers/TeacherController.php';

requireRole(ROLE_ADMIN);

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new TeacherController();
    $controller->softDelete($id);

    $_SESSION['success'] = 'Teacher moved to trash.';
}

header("Location: teachers.php");
exit;