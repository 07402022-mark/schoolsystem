<?php

require_once __DIR__ . '/../../config/db.php';

class AttendanceController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function all()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT attendance.*, students.name AS student_name, subjects.name AS subject_name, teachers.name AS teacher_name
                FROM attendance
                JOIN students ON students.id = attendance.student_id
                LEFT JOIN subjects ON subjects.id = attendance.subject_id
                LEFT JOIN teachers ON teachers.id = attendance.teacher_id
                WHERE attendance.record_status = ?
                ORDER BY attendance.attendance_date DESC
            ");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function byStudent($studentId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT attendance.*, subjects.name AS subject_name
                FROM attendance
                LEFT JOIN subjects ON subjects.id = attendance.subject_id
                WHERE attendance.student_id = ? AND attendance.record_status = ?
                ORDER BY attendance.attendance_date DESC
            ");
            $stmt->execute([$studentId, RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function byTeacher($teacherId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT attendance.*, students.name AS student_name, subjects.name AS subject_name
                FROM attendance
                JOIN students ON students.id = attendance.student_id
                LEFT JOIN subjects ON subjects.id = attendance.subject_id
                WHERE attendance.teacher_id = ? AND attendance.record_status = ?
                ORDER BY attendance.attendance_date DESC
            ");
            $stmt->execute([$teacherId, RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function find($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM attendance WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data)
    {
        try {
            $allowed = [ATT_PRESENT, ATT_ABSENT, ATT_LATE];

            if (!in_array($data['status'], $allowed)) {
                $_SESSION['error'] = 'Invalid attendance status.';
                return false;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO attendance (student_id, subject_id, teacher_id, attendance_date, status)
                VALUES (?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $data['student_id'],
                $data['subject_id'] ?: null,
                $data['teacher_id'] ?: null,
                $data['attendance_date'],
                $data['status']
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE attendance
                SET student_id = ?, subject_id = ?, teacher_id = ?, attendance_date = ?, status = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['student_id'],
                $data['subject_id'] ?: null,
                $data['teacher_id'] ?: null,
                $data['attendance_date'],
                $data['status'],
                $id
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function softDelete($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE attendance SET record_status = ? WHERE id = ?");
            return $stmt->execute([RECORD_DELETED, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}