<?php
require_once __DIR__ . '/../app/controllers/Auth.php';

if (isLoggedIn()) {
    $role = currentUser()['role'];

    if ($role === ROLE_ADMIN) redirectTo('admin/dashboard.php');
    if ($role === ROLE_TEACHER) redirectTo('teacher/dashboard.php');
    if ($role === ROLE_STUDENT) redirectTo('student/dashboard.php');
}

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->login($_POST['email'], $_POST['password'])) {
        $role = currentUser()['role'];

        if ($role === ROLE_ADMIN) redirectTo('admin/dashboard.php');
        if ($role === ROLE_TEACHER) redirectTo('teacher/dashboard.php');
        if ($role === ROLE_STUDENT) redirectTo('student/dashboard.php');
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<section class="auth-page">
    <div class="login-box">

        <h2>Login</h2>

        <form method="POST">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>

        </form>

        <p class="auth-link">
            <a href="forgot_password.php">Forgot Password?</a>
        </p>

        <p class="auth-link">
            Don't have an account?
            <a href="register.php"><strong>Create Account</strong></a>
        </p>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>