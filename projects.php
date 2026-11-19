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
    <title>My Projects</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>My Projects</h1>
    <nav>
        <a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a>
    </nav>
</header>
<main>
    <h2>My Projects</h2>
    <div class="filter-buttons">
        <button class="filter-button" type="button" data-filter="all">All</button>
        <button class="filter-button" type="button" data-filter="web">Web</button>
        <button class="filter-button" type="button" data-filter="animation">Animation</button>
        <button class="filter-button" type="button" data-filter="networking">Networking</button>
    </div>
    <div class="projects">
        <?php foreach ($projects as $project): ?>
            <?php $category = strtolower(trim($project['category'] ?? '')); ?>
            <article class="project-card" data-category="<?= escape($category) ?>">
                <?php if ($project['image_path']): ?><img src="<?= escape($project['image_path']) ?>" alt="<?= escape($project['title']) ?>"><?php endif; ?>
                <h3><?= escape($project['title']) ?></h3>
                <p><?= escape($project['description']) ?></p>
                <p><strong>Technologies:</strong> <?= escape($project['technologies'] ?? '') ?></p>
                <?php if ($project['project_url']): ?><a href="<?= escape($project['project_url']) ?>" target="_blank" rel="noopener noreferrer">View project</a><?php endif; ?>
            </article>
        <?php endforeach; ?>
        <?php if (!$projects): ?><p>No projects have been added yet.</p><?php endif; ?>
    </div>
</main>
<button id="topButton" type="button" aria-label="Scroll to top">↑</button>
<script src="js/script.js"></script>
</body>
</html>
