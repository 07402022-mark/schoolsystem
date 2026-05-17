<?php

require_once __DIR__ . '/../../config/db.php';

class AnnouncementController
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
                SELECT announcements.*, subjects.name AS subject_name
                FROM announcements
                LEFT JOIN subjects ON subjects.id = announcements.subject_id
                WHERE announcements.record_status = ?
                ORDER BY announcements.publish_at DESC
            ");
            $stmt->execute([RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function schoolAnnouncements()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM announcements
                WHERE record_status = ? AND type = ?
                ORDER BY publish_at DESC
            ");
            $stmt->execute([RECORD_ACTIVE, ANNOUNCEMENT_SCHOOL]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function byTeacher($teacherId)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM announcements
                WHERE author_id = ? AND author_role = ? AND record_status = ?
                ORDER BY publish_at DESC
            ");
            $stmt->execute([$teacherId, ROLE_TEACHER, RECORD_ACTIVE]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function find($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM announcements WHERE id = ? LIMIT 1");
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
                INSERT INTO announcements
                (title, message, type, author_role, author_id, subject_id, publish_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $data['title'],
                $data['message'],
                $data['type'],
                $data['author_role'],
                $data['author_id'],
                $data['subject_id'] ?: null,
                $data['publish_at'] ?: date('Y-m-d H:i:s')
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
                UPDATE announcements
                SET title = ?, message = ?, type = ?, subject_id = ?, publish_at = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['title'],
                $data['message'],
                $data['type'],
                $data['subject_id'] ?: null,
                $data['publish_at'],
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
            $stmt = $this->pdo->prepare("UPDATE announcements SET record_status = ? WHERE id = ?");
            return $stmt->execute([RECORD_DELETED, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function submitAnswer($announcementId, $studentId, $answer)
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO submissions (announcement_id, student_id, answer)
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$announcementId, $studentId, $answer]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
}