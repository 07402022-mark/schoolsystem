<?php

require_once __DIR__ . '/../../config/db.php';

class ReportController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function counts()
    {
        try {
            return [
                'students' => $this->countTable('students'),
                'teachers' => $this->countTable('teachers'),
                'subjects' => $this->countTable('subjects'),
                'grades' => $this->countTable('grades'),
                'attendance' => $this->countTable('attendance'),
                'announcements' => $this->countTable('announcements')
            ];
        } catch (Exception $e) {
            return [];
        }
    }

    private function countTable($table)
    {
        $allowed = [
            'students',
            'teachers',
            'subjects',
            'grades',
            'attendance',
            'announcements'
        ];

        if (!in_array($table, $allowed)) {
            return ZERO;
        }

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS total
            FROM {$table}
            WHERE record_status = ?
        ");
        $stmt->execute([RECORD_ACTIVE]);
        $row = $stmt->fetch();

        return $row['total'] ?? ZERO;
    }

    public function gradeSummary()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    students.name AS student_name,
                    subjects.name AS subject_name,
                    grades.grade,
                    grades.remarks
                FROM grades
                JOIN students ON students.id = grades.student_id
                JOIN subjects ON subjects.id = grades.subject_id
                WHERE grades.record_status = ?
                ORDER BY students.name ASC
            ");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function attendanceSummary()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    students.name AS student_name,
                    subjects.name AS subject_name,
                    attendance.attendance_date,
                    attendance.status
                FROM attendance
                JOIN students ON students.id = attendance.student_id
                LEFT JOIN subjects ON subjects.id = attendance.subject_id
                WHERE attendance.record_status = ?
                ORDER BY attendance.attendance_date DESC
            ");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}