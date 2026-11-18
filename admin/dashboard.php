<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$projectCount = (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$skillCount = (int) $pdo->query('SELECT COUNT(*) FROM skills')->fetchColumn();
$messageCount = (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$experienceCount = (int) $pdo->query('SELECT COUNT(*) FROM experience')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Aika's Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <h1>Admin Dashboard</h1>
        <p>Signed in as <?= escape($_SESSION['admin_email'] ?? '') ?></p>
        <form method="post" action="logout.php">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <button type="submit">Log out</button>
        </form>
        <section>
            <h2>Website Statistics</h2>
            <ul>
                <li>Projects: <?= $projectCount ?></li>
                <li>Skills: <?= $skillCount ?></li>
                <li>Messages: <?= $messageCount ?></li>
                <li>Experience records: <?= $experienceCount ?></li>
            </ul>
        </section>
        <p>Content management pages are being added in the next development steps.</p>
        <p><a href="../index.php">View public portfolio</a></p>
    </main>
</body>
</html>
