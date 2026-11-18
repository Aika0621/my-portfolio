<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$education = $pdo->query('SELECT * FROM education ORDER BY id DESC')->fetchAll();
$experience = $pdo->query('SELECT * FROM experience ORDER BY id DESC')->fetchAll();
$achievements = $pdo->query('SELECT * FROM achievements ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Resume | Aika's Portfolio</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>My Resume</h1><nav><a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a></nav></header>
<main>
    <section><h2>Education</h2>
        <?php foreach ($education as $item): ?><article><h3><?= escape($item['qualification']) ?></h3><p><?= escape($item['school']) ?> · <?= escape($item['start_date']) ?> – <?= escape($item['end_date']) ?></p><p><?= nl2br(escape($item['details'])) ?></p></article><?php endforeach; ?>
    </section>
    <section><h2>Experience</h2>
        <?php foreach ($experience as $item): ?><article><h3><?= escape($item['position']) ?></h3><p><?= escape($item['organization']) ?> · <?= escape($item['start_date']) ?> – <?= escape($item['end_date']) ?></p><p><?= nl2br(escape($item['details'])) ?></p></article><?php endforeach; ?>
    </section>
    <section><h2>Achievements</h2>
        <?php foreach ($achievements as $item): ?><article><h3><?= escape($item['title']) ?></h3><p><?= escape($item['organization']) ?> · <?= escape($item['achievement_date']) ?></p><p><?= nl2br(escape($item['details'])) ?></p></article><?php endforeach; ?>
    </section>
</main>
<script src="js/script.js"></script>
</body>
</html>
