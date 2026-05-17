<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new GradeController();
    $grade = $controller->find($id);

    if ($grade && $grade['teacher_id'] == $teacherId) {
        $controller->softDelete($id);
        $_SESSION['success'] = 'Grade deleted.';
    }
}

redirectTo('teacher/grades.php');