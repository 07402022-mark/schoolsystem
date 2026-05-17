<?php
require_once __DIR__ . '/../app/controllers/PasswordRequestController.php';

$controller = new PasswordRequestController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->create($_POST)) {
        $_SESSION['success'] = 'Password request sent. Please wait for admin.';
        redirectTo('forgot_password.php');
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="login-box">
    <h2>Forgot Password</h2>

    <form method="POST">
        <label>Student ID</label>
        <input name="student_id" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Message</label>
        <textarea name="message"></textarea>

        <button type="submit">Send Request</button>
    </form>

    <p><a href="login.php">Back to Login</a></p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>