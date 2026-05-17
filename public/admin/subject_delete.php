<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/constant.php';

requireRole(ROLE_ADMIN);

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: teacher_subjects.php");
exit;