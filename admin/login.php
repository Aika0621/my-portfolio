<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/security.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errorMessage = '';
$now = time();

if (!isset($_SESSION['login_attempts']) || $now - ($_SESSION['login_window'] ?? 0) > 900) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['login_window'] = $now;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    if ($_SESSION['login_attempts'] >= 5) {
        $errorMessage = 'Too many login attempts. Please wait 15 minutes before trying again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (filter_var($email, FILTER_VALIDATE_EMAIL) && is_string($password)) {
            $statement = $pdo->prepare('SELECT id, email, password_hash FROM admins WHERE email = ? LIMIT 1');
            $statement->execute([$email]);
            $admin = $statement->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['login_attempts'] = 0;
                header('Location: dashboard.php');
                exit;
            }
        }

        $_SESSION['login_attempts']++;
        $errorMessage = 'The email or password is incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In | Aika's Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-area admin-login-page">
<main class="admin-login-wrap">
    <section class="admin-login-card" aria-labelledby="login-title">
        <a class="admin-login-brand" href="../index.php" aria-label="Back to portfolio home">
            <span class="admin-login-brand-mark">A</span>
            <span>Aika <strong>Portfolio</strong></span>
        </a>

        <div class="admin-login-label"><span class="admin-login-label-dot"></span> ADMIN PORTAL</div>
        <h1 id="login-title">Welcome back</h1>
        <p class="admin-login-intro">Sign in to manage your portfolio content.</p>

        <?php if ($errorMessage !== ''): ?>
            <p class="admin-alert" role="alert"><?= escape($errorMessage) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php" class="admin-login-form">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <p>
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" required maxlength="190" autocomplete="username" placeholder="you@example.com" value="<?= escape(trim($_POST['email'] ?? '')) ?>">
            </p>
            <p>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
            </p>
            <button type="submit">Sign in to dashboard <span aria-hidden="true">→</span></button>
        </form>

        <div class="admin-login-footer">
            <a class="admin-back-link" href="../index.php">← Back to portfolio</a>
            <span>Secure admin access</span>
        </div>
    </section>
</main>
</body>
</html>
