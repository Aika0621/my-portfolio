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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Aika's Portfolio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-area">
<header class="admin-topbar">
    <a class="admin-brand" href="dashboard.php">Aika <span>Admin</span></a>
    <nav aria-label="Admin navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="projects.php">Projects</a>
        <a href="messages.php">Messages<?= $unreadCount > 0 ? ' (' . $unreadCount . ')' : '' ?></a>
        <a href="../index.php">View site</a>
    </nav>
</header>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, <?= escape($_SESSION['admin_email'] ?? '') ?>. Here's your portfolio overview.</p>
        </div>
        <form method="post" action="logout.php">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <button type="submit">Log out</button>
        </form>
    </div>
    <section class="admin-stat-grid" aria-label="Portfolio statistics">
        <div class="admin-stat-card"><span>Portfolio projects</span><strong><?= $projectCount ?></strong></div>
        <div class="admin-stat-card"><span>Skills</span><strong><?= $skillCount ?></strong></div>
        <div class="admin-stat-card"><span>Messages</span><strong><?= $messageCount ?></strong></div>
        <div class="admin-stat-card"><span>Experience records</span><strong><?= $experienceCount ?></strong></div>
    </section>
    <section class="admin-card">
        <h2>Manage your portfolio</h2>
        <div class="admin-manage-grid">
            <a href="profile.php">Personal profile</a>
            <a href="skills.php">Skills and expertise</a>
            <a href="projects.php">Projects and images</a>
            <a href="records.php?type=education">Education</a>
            <a href="records.php?type=experience">Experience</a>
            <a href="records.php?type=achievements">Achievements</a>
            <a href="settings.php">Website settings</a>
            <a href="messages.php">Contact messages<?= $unreadCount > 0 ? ' · ' . $unreadCount . ' unread' : '' ?></a>
        </div>
    </section>
</main>
</body>
</html>
