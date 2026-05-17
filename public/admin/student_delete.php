<?php
require_once __DIR__ . '/../../app/controllers/StudentController.php';

requireRole(ROLE_ADMIN);

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new StudentController();
    $controller->softDelete($id);
    $_SESSION['success'] = 'Student moved to trash.';
}

redirectTo('admin/students.php');