<?php

require_once __DIR__ . '/../../config/db.php';

class StudentController
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
            $stmt = $this->pdo->prepare("SELECT * FROM students WHERE record_status = ? ORDER BY id DESC");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function find($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    }

    private function generateStudentNumber()
    {
        do {
            $studentNumber = str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);

            $check = $this->pdo->prepare("SELECT id FROM students WHERE student_number = ? LIMIT 1");
            $check->execute([$studentNumber]);

        } while ($check->fetch());

        return $studentNumber;
    }

    public function create($data)
    {
        try {
            if (
                empty($data['name']) ||
                empty($data['email']) ||
                empty($data['course']) ||
                empty($data['year_level']) ||
                empty($data['password'])
            ) {
                $_SESSION['error'] = 'All fields are required.';
                return false;
            }

            if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                $_SESSION['error'] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
                return false;
            }

            $this->pdo->beginTransaction();

            $studentNumber = $this->generateStudentNumber();

            $stmt = $this->pdo->prepare("
                INSERT INTO students (student_number, name, email, course, year_level)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $studentNumber,
                trim($data['name']),
                trim($data['email']),
                trim($data['course']),
                trim($data['year_level'])
            ]);

            $studentId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare("
                INSERT INTO users (name, email, password, role, status, student_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                trim($data['name']),
                trim($data['email']),
                password_hash($data['password'], PASSWORD_DEFAULT),
                ROLE_STUDENT,
                STATUS_ACTIVE,
                $studentId
            ]);

            $this->pdo->commit();

            $_SESSION['success'] = 'Student added. Student Number: ' . $studentNumber;
            return true;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE students
                SET name = ?, email = ?, course = ?, year_level = ?, academic_status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                trim($data['name']),
                trim($data['email']),
                trim($data['course']),
                trim($data['year_level']),
                $data['academic_status'],
                $id
            ]);

            $stmt = $this->pdo->prepare("
                UPDATE users
                SET name = ?, email = ?
                WHERE student_id = ? AND role = ?
            ");

            $stmt->execute([
                trim($data['name']),
                trim($data['email']),
                $id,
                ROLE_STUDENT
            ]);

            return true;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function softDelete($id)
    {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("UPDATE students SET record_status = ? WHERE id = ?");
            $stmt->execute([RECORD_DELETED, $id]);

            $stmt = $this->pdo->prepare("
                UPDATE users
                SET status = ?
                WHERE student_id = ? AND role = ?
            ");

            $stmt->execute([STATUS_DELETED, $id, ROLE_STUDENT]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }
}