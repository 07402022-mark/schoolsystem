<?php

require_once __DIR__ . '/../../config/db.php';

class Auth
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM users
                WHERE email = ? AND status = ?
                LIMIT 1
            ");

            $stmt->execute([
                trim($email),
                STATUS_ACTIVE
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['error'] = 'Invalid email or password.';
                return false;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'student_id' => $user['student_id'] ?? null,
                'teacher_id' => $user['teacher_id'] ?? null,
                'status' => $user['status']
            ];

            return true;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function createAccount($name, $email, $password, $role)
    {
        try {
            $role = strtolower(trim($role));

            if (!in_array($role, ['admin', 'teacher'])) {
                $_SESSION['error'] = 'Students are not allowed to create accounts.';
                return false;
            }

            $check = $this->pdo->prepare("
                SELECT id FROM users
                WHERE email = ?
                LIMIT 1
            ");
            $check->execute([trim($email)]);

            if ($check->fetch()) {
                $_SESSION['error'] = 'Email already exists.';
                return false;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO users 
                (name, email, password, role, status, student_id, teacher_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $teacherId = null;

            if ($role === 'teacher') {
                $teacherId = uniqid('TCH-');
            }

            return $stmt->execute([
                trim($name),
                trim($email),
                password_hash($password, PASSWORD_DEFAULT),
                $role,
                STATUS_ACTIVE,
                null,
                $teacherId
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        redirectTo('login.php');
    }
}