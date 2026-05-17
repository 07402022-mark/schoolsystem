<?php
require_once __DIR__ . '/../../app/controllers/ReportController.php';

requireRole(ROLE_ADMIN);

$controller = new ReportController();
$counts = $controller->counts();
$grades = $controller->gradeSummary();
$attendance = $controller->attendanceSummary();
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php require_once __DIR__ . '/../../includes/admin_sidebar.php'; ?>

<section class="content">
    <h1>Reports</h1>

    <p>
        <a class="btn" href="export_reports.php">Export CSV</a>
        <button onclick="printPage()">Print</button>
    </p>

    <div class="grid">
        <div class="card"><h3>Students</h3><p><?= e($counts['students'] ?? ZERO) ?></p></div>
        <div class="card"><h3>Teachers</h3><p><?= e($counts['teachers'] ?? ZERO) ?></p></div>
        <div class="card"><h3>Subjects</h3><p><?= e($counts['subjects'] ?? ZERO) ?></p></div>
        <div class="card"><h3>Grades</h3><p><?= e($counts['grades'] ?? ZERO) ?></p></div>
    </div>

    <div class="card">
        <h3>Grade Summary</h3>

        <table>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>

            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?= e($grade['student_name']) ?></td>
                    <td><?= e($grade['subject_name']) ?></td>
                    <td><?= e($grade['grade']) ?></td>
                    <td><?= e($grade['remarks']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card">
        <h3>Attendance Summary</h3>

        <table>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php foreach ($attendance as $row): ?>
                <tr>
                    <td><?= e($row['student_name']) ?></td>
                    <td><?= e($row['subject_name'] ?? 'None') ?></td>
                    <td><?= e($row['attendance_date']) ?></td>
                    <td><?= e($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>