<?php

require_once __DIR__ . '/constant.php';

function loadEnvFile($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $_ENV[trim($key)] = trim($value);
    }
}

loadEnvFile(__DIR__ . '/../.env');

function envValue($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(envValue('SESSION_NAME', 'SCHOOL_SESSION'));
        session_start();
    }

    $dsn = "mysql:host=" . envValue('DB_HOST', 'localhost') .
        ";port=" . envValue('DB_PORT', '3306') .
        ";dbname=" . envValue('DB_NAME', 'school_db') .
        ";charset=utf8mb4";

    $pdo = new PDO($dsn, envValue('DB_USER', 'root'), envValue('DB_PASS', ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function baseUrl($path = '')
{
    return rtrim(envValue('APP_URL', '/schoolsystem/public'), '/') . '/' . ltrim($path, '/');
}

function redirectTo($path)
{
    header("Location: " . baseUrl($path));
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function currentUser()
{
    return $_SESSION['user'] ?? null;
}

function dashboardByRole($role)
{
    if ($role === ROLE_ADMIN) {
        return 'admin/dashboard.php';
    }

    if ($role === ROLE_TEACHER) {
        return 'teacher/dashboard.php';
    }

    if ($role === ROLE_STUDENT) {
        return 'student/dashboard.php';
    }

    return 'login.php';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirectTo('login.php');
    }
}

function requireRole($role)
{
    requireLogin();

    $currentRole = $_SESSION['user']['role'] ?? '';

    if ($currentRole !== $role) {
        redirectTo(dashboardByRole($currentRole));
    }
}

function requireAnyRole($roles)
{
    requireLogin();

    $currentRole = $_SESSION['user']['role'] ?? '';

    if (!in_array($currentRole, $roles)) {
        redirectTo(dashboardByRole($currentRole));
    }
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}