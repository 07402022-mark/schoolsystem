<?php
require_once __DIR__ . '/../../app/controllers/AttendanceController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$id = $_GET['id'] ?? null;

if ($id) {
    $controller = new AttendanceController();
    $row = $controller->find($id);

    if ($row && $row['teacher_id'] == $teacherId) {
        $controller->softDelete($id);
        $_SESSION['success'] = 'Attendance deleted.';
    }
}

redirectTo('teacher/attendance.php');