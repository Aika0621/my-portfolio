<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$skills = $pdo->query('SELECT name, category, level FROM skills ORDER BY category, name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Skills | Aika's Portfolio</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>My Skills</h1><nav><a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a></nav></header>
<main>
    <h2>Skills and Technical Expertise</h2>
    <div class="skills">
        <?php foreach ($skills as $skill): ?>
            <article><h3><?= escape($skill['name']) ?></h3><p><?= escape($skill['category']) ?></p><progress max="100" value="<?= (int) $skill['level'] ?>"><?= (int) $skill['level'] ?>%</progress></article>
        <?php endforeach; ?>
    </div>
</main>
<script src="js/script.js"></script>
</body>
</html>
