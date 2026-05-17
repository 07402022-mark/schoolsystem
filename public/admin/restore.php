<?php
require_once __DIR__ . '/../../config/db.php';

requireRole(ROLE_ADMIN);

$allowedTables = [
    'students',
    'teachers',
    'subjects',
    'grades',
    'attendance',
    'announcements'
];

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? null;

if (!in_array($table, $allowedTables) || !$id) {
    $_SESSION['error'] = 'Invalid restore request.';
    redirectTo('admin/trash.php');
}

try {
    $stmt = $pdo->prepare("UPDATE {$table} SET record_status = ? WHERE id = ?");
    $stmt->execute([RECORD_ACTIVE, $id]);

    $_SESSION['success'] = 'Record restored.';
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

redirectTo('admin/trash.php');
