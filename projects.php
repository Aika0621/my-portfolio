<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$projects = $pdo->query('SELECT title, description, technologies, category, image_path, project_url FROM projects ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | Aika's Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="projects-page">
<header>
    <h1>My Projects</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="skills.php">Skills</a>
        <a href="projects.php">Projects</a>
        <a href="resume.php">Resume</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>
<main>
    <h2>My Projects</h2>
    <div class="projects">
        <?php foreach ($projects as $project): ?>
            <article class="project-card">
                <?php if ($project['image_path']): ?>
                    <div class="project-image-frame">
                        <img src="<?= escape($project['image_path']) ?>" alt="<?= escape($project['title']) ?>">
                    </div>
                <?php endif; ?>
                <h3><?= escape($project['title']) ?></h3>
                <p><?= escape($project['description']) ?></p>
                <p><strong>Technologies:</strong> <?= escape($project['technologies'] ?? '') ?></p>
                <?php if ($project['project_url']): ?>
                    <a href="<?= escape($project['project_url']) ?>" target="_blank" rel="noopener noreferrer">View project</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (!$projects): ?>
            <p>No projects have been added yet.</p>
        <?php endif; ?>
    </div>
</main>
<script src="js/script.js"></script>
</body>
</html>
