<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$profile = $pdo->query('SELECT name, introduction, biography, email, image_path FROM profile ORDER BY id LIMIT 1')->fetch();
$skills = $pdo->query('SELECT name, category, level FROM skills ORDER BY category, name')->fetchAll();
$projects = $pdo->query('SELECT title, description, technologies, image_path, project_url FROM projects ORDER BY id DESC')->fetchAll();
$settings = $pdo->query("SELECT setting_value FROM settings WHERE setting_name = 'site_title' LIMIT 1")->fetchColumn();

if (!$profile) {
    $profile = ['name' => 'Aika', 'introduction' => 'Information Technology Student', 'biography' => 'I enjoy learning about technology and creating simple projects.', 'email' => '', 'image_path' => null];
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= escape($settings ?: ($profile['name'] . ' | Portfolio')) ?></title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header>
    <h1><?= escape($settings ?: ($profile['name'] . "'s Portfolio")) ?></h1>
    <nav><a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a></nav>
    <button id="themeButton" type="button">Dark Mode</button>
</header>
<main>
    <section class="home">
        <div class="home-text"><h2>Hello! I'm <?= escape($profile['name']) ?>.</h2><h3><?= escape($profile['introduction']) ?></h3><p><?= escape($profile['biography']) ?></p><a href="about.php" class="button">About Me</a></div>
        <div class="home-image"><img src="<?= escape($profile['image_path'] ?: 'images/profile.jpg') ?>" alt="Profile picture"></div>
    </section>
    <section><h2>Skills</h2><div class="skills">
        <?php foreach ($skills as $skill): ?><article><h3><?= escape($skill['name']) ?></h3><p><?= escape($skill['category']) ?> · <?= (int) $skill['level'] ?>%</p></article><?php endforeach; ?>
    </div><p><a href="skills.php">View all skills</a></p></section>
    <section><h2>Projects</h2><div class="projects">
        <?php foreach (array_slice($projects, 0, 3) as $project): ?><article class="project-card"><?php if ($project['image_path']): ?><img src="<?= escape($project['image_path']) ?>" alt="<?= escape($project['title']) ?>"><?php endif; ?><h3><?= escape($project['title']) ?></h3><p><?= escape($project['description']) ?></p><p><strong>Technologies:</strong> <?= escape($project['technologies'] ?? '') ?></p></article><?php endforeach; ?>
    </div><p><a href="projects.php">View all projects</a></p></section>
</main>
<footer><p>&copy; <?= date('Y') ?> <?= escape($profile['name']) ?></p><p><a href="admin/login.php">Admin</a></p></footer>
<button id="topButton" type="button">↑</button><script src="js/script.js"></script>
</body>
</html>
