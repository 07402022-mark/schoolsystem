<?php

require_once __DIR__ . '/../../config/db.php';

class SubjectController
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
                SELECT subjects.*, teachers.name AS teacher_name
                FROM subjects
                LEFT JOIN teachers ON teachers.id = subjects.teacher_id
                WHERE subjects.record_status = ?
                ORDER BY subjects.id DESC
            ");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function byTeacher($teacherId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM subjects
                WHERE teacher_id = ? AND record_status = ?
                ORDER BY id DESC
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
            $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO subjects (code, name, year_level, course, teacher_id)
                VALUES (?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $data['code'],
                $data['name'],
                $data['year_level'],
                $data['course'],
                $data['teacher_id'] ?: null
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
                UPDATE subjects
                SET code = ?, name = ?, year_level = ?, course = ?, teacher_id = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['code'],
                $data['name'],
                $data['year_level'],
                $data['course'],
                $data['teacher_id'] ?: null,
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
            $stmt = $this->pdo->prepare("UPDATE subjects SET record_status = ? WHERE id = ?");
            return $stmt->execute([RECORD_DELETED, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}