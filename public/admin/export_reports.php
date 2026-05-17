<?php
require_once __DIR__ . '/../../app/controllers/ReportController.php';

requireRole(ROLE_ADMIN);

$controller = new ReportController();
$grades = $controller->gradeSummary();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="grade_reports.csv"');

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