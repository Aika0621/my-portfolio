<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$profile = $pdo->query('SELECT name, biography, email, phone, location, image_path FROM profile ORDER BY id LIMIT 1')->fetch();
if (!$profile) {
    $profile = ['name' => 'Aika', 'biography' => 'I am an Information Technology student. I enjoy learning about technology and improving my skills.', 'email' => '', 'phone' => '', 'location' => '', 'image_path' => ''];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>About Me</h1>
    <nav>
        <a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a>
    </nav>
</header>
<main>
    <h2>About Me</h2>
    <?php if ($profile['image_path']): ?><p><img src="<?= escape($profile['image_path']) ?>" alt="<?= escape($profile['name']) ?>" style="width:180px;height:180px;object-fit:cover;border-radius:50%;"></p><?php endif; ?>
    <p>Hello! My name is <?= escape($profile['name']) ?>. <?= nl2br(escape($profile['biography'])) ?></p>
    <h3>Education</h3>
    <p>I am currently studying Information Technology and learning different areas of technology and computer programming.</p>
    <h3>My Goals</h3>
    <p>My goal is to learn and improve my skills in the area of Information Technology.</p>
    <h3>My Interests</h3>
    <p>I am interested in technology, learning new things, and trying different creative activities.</p>
    <?php if ($profile['location']): ?><p>Location: <?= escape($profile['location']) ?></p><?php endif; ?>
    <?php if ($profile['email']): ?><p>Email: <?= escape($profile['email']) ?></p><?php endif; ?>
    <?php if ($profile['phone']): ?><p>Phone: <?= escape($profile['phone']) ?></p><?php endif; ?>
</main>
<button id="topButton" type="button" aria-label="Scroll to top">↑</button>
<script src="js/script.js"></script>
</body>
</html>
