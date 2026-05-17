<?php
require_once __DIR__ . '/../../app/controllers/PasswordRequestController.php';

requireRole(ROLE_STUDENT);

$studentNumber = $_GET['student_number'] ?? '';

if (!$studentNumber) {
    redirectTo('student/messages.php');
}

$controller = new PasswordRequestController();
$messages = $controller->byStudentNumber($studentNumber);

header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'messages' => $messages
]);
exit;