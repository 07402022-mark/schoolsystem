<?php
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title><?= e(envValue('APP_NAME', 'SchoolSystem')) ?></title>

    <link rel="stylesheet" href="/schoolsystem/public/css/style.css?v=999">
    <link rel="stylesheet" href="/schoolsystem/public/css/print.css?v=999" media="print">
</head>

<body>

<header class="topbar">
    <h2><?= e(envValue('APP_NAME', 'SchoolSystem')) ?></h2>

    <?php if (isLoggedIn()): ?>
        <div>
            <?= e($_SESSION['user']['name']) ?>
            |
            <a href="<?= baseUrl('logout.php') ?>">Logout</a>
        </div>
    <?php endif; ?>
</header>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert success">
        <?= e($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert error">
        <?= e($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<main class="layout">