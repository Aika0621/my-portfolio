<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$education = $pdo->query('SELECT * FROM education ORDER BY id DESC')->fetchAll();
$experience = $pdo->query('SELECT * FROM experience ORDER BY id DESC')->fetchAll();
$achievements = $pdo->query('SELECT * FROM achievements ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume / Experience</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Resume / Experience</h1>
    <nav>
        <a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a>
    </nav>
</header>
<main>
    <h2>Education</h2>
    <?php foreach ($education as $item): ?><article class="resume-box"><h3><?= escape($item['qualification']) ?></h3><p><?= escape($item['school']) ?> · <?= escape($item['start_date']) ?> – <?= escape($item['end_date']) ?></p><p><?= nl2br(escape($item['details'])) ?></p></article><?php endforeach; ?>
    <h2>Activities</h2>
    <div class="resume-box"><ul>
        <?php foreach ($experience as $item): ?><li><?= escape($item['position']) ?> — <?= escape($item['organization']) ?></li><?php endforeach; ?>
        <?php if (!$experience): ?><li>Experience and activities will be added soon.</li><?php endif; ?>
    </ul></div>
    <h2>Training and Learning</h2>
    <div class="resume-box"><p>I continue to learn about web development, programming, databases, networking, and multimedia through my Information Technology studies.</p></div>
    <h2>Achievements</h2>
    <?php foreach ($achievements as $item): ?><article class="resume-box"><h3><?= escape($item['title']) ?></h3><p><?= escape($item['organization']) ?> · <?= escape($item['achievement_date']) ?></p><p><?= nl2br(escape($item['details'])) ?></p></article><?php endforeach; ?>
</main>
<button id="topButton" type="button" aria-label="Scroll to top">↑</button>
<script src="js/script.js"></script>
</body>
</html>
