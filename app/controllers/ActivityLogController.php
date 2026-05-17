<?php

require_once __DIR__ . '/../../config/db.php';

class ActivityLogController
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
                SELECT * FROM activity_logs
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function create($userId, $role, $action, $details)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO activity_logs (user_id, role, action, details)
                VALUES (?, ?, ?, ?)
            ");

            return $stmt->execute([
                $userId,
                $role,
                $action,
                $details
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
}