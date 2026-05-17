<?php
require_once __DIR__ . '/../../app/controllers/GradeController.php';

requireRole(ROLE_TEACHER);

$teacherId = currentUser()['teacher_id'];

$controller = new GradeController();
$grades = $controller->byTeacher($teacherId);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="class_records.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Student', 'Subject', 'Grade', 'Remarks']);

foreach ($grades as $grade) {
    fputcsv($output, [
        $grade['student_name'],
        $grade['subject_name'],
        $grade['grade'],
        $grade['remarks']
    ]);
}

fclose($output);
exit;