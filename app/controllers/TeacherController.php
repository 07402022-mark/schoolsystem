<?php

class TeacherController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM teachers
            WHERE record_status = 'active'
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO teachers
            (teacher_number, name, email, department, record_status)
            VALUES (?, ?, ?, ?, 'active')
        ");

        return $stmt->execute([
            $data['teacher_number'],
            $data['name'],
            $data['email'],
            $data['department']
        ]);
    }

    public function softDelete($id)
    {
        $stmt = $this->pdo->prepare("
            UPDATE teachers
            SET record_status = 'deleted'
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}