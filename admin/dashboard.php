<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$projectCount = (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$skillCount = (int) $pdo->query('SELECT COUNT(*) FROM skills')->fetchColumn();
$messageCount = (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$unreadCount = (int) $pdo->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
$experienceCount = (int) $pdo->query('SELECT COUNT(*) FROM experience')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Dashboard | Aika's Portfolio</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<main>
    <h1>Admin Dashboard</h1>
    <p>Signed in as <?= escape($_SESSION['admin_email'] ?? '') ?></p>
    <form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><button type="submit">Log out</button></form>
    <section>
        <h2>Website Statistics</h2>
        <ul><li>Projects: <?= $projectCount ?></li><li>Skills: <?= $skillCount ?></li><li>Messages: <?= $messageCount ?> (<?= $unreadCount ?> unread)</li><li>Experience records: <?= $experienceCount ?></li></ul>
    </section>
    <section>
        <h2>Manage Portfolio</h2>
        <ul>
            <li><a href="profile.php">Personal profile</a></li>
            <li><a href="skills.php">Skills</a></li>
            <li><a href="projects.php">Projects</a></li>
            <li><a href="records.php?type=education">Education</a></li>
            <li><a href="records.php?type=experience">Experience</a></li>
            <li><a href="records.php?type=achievements">Achievements</a></li>
            <li><a href="settings.php">Website settings</a></li>
            <li><a href="messages.php">Contact messages</a></li>
        </ul>
    </section>
    <p><a href="../index.php">View public portfolio</a></p>
</main>
</body>
</html>
