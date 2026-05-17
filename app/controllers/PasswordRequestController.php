<?php

require_once __DIR__ . '/../../config/db.php';

class PasswordRequestController
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
                SELECT * FROM password_requests
                WHERE status != ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([STATUS_DELETED]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function create($data)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO password_requests (student_id, email, message, status)
                VALUES (?, ?, ?, ?)
            ");

            return $stmt->execute([
                trim($data['student_id']),
                trim($data['email']),
                trim($data['message'] ?? ''),
                'pending'
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function reply($id, $adminReply, $newPassword)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE password_requests
                SET admin_reply = ?, new_password_text = ?, status = ?, replied_at = NOW()
                WHERE id = ?
            ");

            return $stmt->execute([
                $adminReply,
                $newPassword,
                REQUEST_DONE,
                $id
            ]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function byStudentId($studentId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM password_requests
                WHERE student_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$studentId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function markSeen($id)
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE password_requests 
                SET student_seen = ?
                WHERE id = ?
            ");
            return $stmt->execute([ONE, $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}