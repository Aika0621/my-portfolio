<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$profile = $pdo->query('SELECT name, biography, email, phone, location, image_path FROM profile ORDER BY id LIMIT 1')->fetch();
if (!$profile) {
    $profile = ['name' => 'Aika', 'biography' => '', 'email' => '', 'phone' => '', 'location' => '', 'image_path' => ''];
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>About | <?= escape($profile['name']) ?></title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>About Me</h1><nav><a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a></nav></header>
<main>
    <section>
        <?php if ($profile['image_path']): ?><img src="<?= escape($profile['image_path']) ?>" alt="Profile picture"><?php endif; ?>
        <h2><?= escape($profile['name']) ?></h2>
        <p><?= nl2br(escape($profile['biography'])) ?></p>
        <?php if ($profile['location']): ?><p>Location: <?= escape($profile['location']) ?></p><?php endif; ?>
        <?php if ($profile['email']): ?><p>Email: <?= escape($profile['email']) ?></p><?php endif; ?>
        <?php if ($profile['phone']): ?><p>Phone: <?= escape($profile['phone']) ?></p><?php endif; ?>
    </section>
</main>
<script src="js/script.js"></script>
</body>
</html>
