<?php

require_once __DIR__ . '/../../config/db.php';

class GradeController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    private function remarks($grade)
    {
        if ($grade >= GRADE_EXCELLENT) {
            return 'Excellent';
        }

        if ($grade >= GRADE_PASSING) {
            return 'Passed';
        }

        return 'Failed';
    }

    public function all()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT grades.*, students.name AS student_name, subjects.name AS subject_name, teachers.name AS teacher_name
                FROM grades
                JOIN students ON students.id = grades.student_id
                JOIN subjects ON subjects.id = grades.subject_id
                LEFT JOIN teachers ON teachers.id = grades.teacher_id
                WHERE grades.record_status = ?
                ORDER BY grades.id DESC
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
                SELECT grades.*, subjects.code, subjects.name AS subject_name
                FROM grades
                JOIN subjects ON subjects.id = grades.subject_id
                WHERE grades.student_id = ? AND grades.record_status = ?
                ORDER BY subjects.code ASC
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
                SELECT grades.*, students.name AS student_name, subjects.name AS subject_name
                FROM grades
                JOIN students ON students.id = grades.student_id
                JOIN subjects ON subjects.id = grades.subject_id
                WHERE grades.teacher_id = ? AND grades.record_status = ?
                ORDER BY grades.id DESC
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
            $stmt = $this->pdo->prepare("SELECT * FROM grades WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data)
    {
        try {
            $grade = (float)$data['grade'];

            if ($grade < GRADE_MIN || $grade > GRADE_MAX) {
                $_SESSION['error'] = 'Grade must be between ' . GRADE_MIN . ' and ' . GRADE_MAX;
                return false;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO grades (student_id, subject_id, teacher_id, grade, remarks)
                VALUES (?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $data['student_id'],
                $data['subject_id'],
                $data['teacher_id'] ?: null,
                $grade,
                $this->remarks($grade)
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $grade = (float)$data['grade'];

            if ($grade < GRADE_MIN || $grade > GRADE_MAX) {
                $_SESSION['error'] = 'Grade must be between ' . GRADE_MIN . ' and ' . GRADE_MAX;
                return false;
            }

            $stmt = $this->pdo->prepare("
                UPDATE grades
                SET student_id = ?, subject_id = ?, teacher_id = ?, grade = ?, remarks = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['student_id'],
                $data['subject_id'],
                $data['teacher_id'] ?: null,
                $grade,
                $this->remarks($grade),
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
            $stmt = $this->pdo->prepare("UPDATE grades SET record_status = ? WHERE id = ?");
            return $stmt->execute([RECORD_DELETED, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}