<?php
require_once __DIR__ . '/../app/controllers/Auth.php';

if (isLoggedIn()) {
    redirectTo('login.php');
}

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->createAccount(
        $_POST['name'],
        $_POST['email'],
        $_POST['password'],
        $_POST['role']
    )) {
        $_SESSION['success'] = 'Account created successfully. You can now login.';
        redirectTo('login.php');
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="login-box">
    <h2>Create Account</h2>

    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="teacher">Teacher</option>
        </select>

        <button type="submit">Create Account</button>
    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>