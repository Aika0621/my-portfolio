<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$message = '';
$profile = $pdo->query('SELECT name, email FROM profile ORDER BY id LIMIT 1')->fetch();
if (!$profile) {
    $profile = ['name' => 'Aika', 'email' => ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $contactMessage = trim($_POST['message'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($name) < 2 || strlen($subject) < 2 || strlen($contactMessage) < 10) {
        $message = 'Please complete all fields with valid information.';
    } elseif (trim($_POST['website'] ?? '') !== '') {
        $message = 'Your message could not be sent.';
    } else {
        $statement = $pdo->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $statement->execute([$name, $email, $subject, $contactMessage]);
        $message = 'Thank you. Your message has been sent.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Contact Me</h1>
    <nav>
        <a href="index.php">Home</a><a href="about.php">About</a><a href="skills.php">Skills</a><a href="projects.php">Projects</a><a href="resume.php">Resume</a><a href="contact.php">Contact</a>
    </nav>
</header>
<main>
    <h2>Get In Touch</h2>
    <p>If you would like to contact me, you can send me a message using the form below.</p>
    <div class="contact-box">
        <h3>Contact Information</h3>
        <p>Email: <?= escape($profile['email'] ?: 'aikamariegrafe5@email.com') ?></p>
        <h3>Social Links</h3>
        <p><a href="#">Facebook</a> | <a href="#">Instagram</a> | <a href="#">GitHub</a></p>
    </div>
    <div class="contact-box">
        <h3>Send Me a Message</h3>
        <?php if ($message !== ''): ?><p id="formMessage" role="status"><?= escape($message) ?></p><?php endif; ?>
        <form id="contactForm" method="post">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <div style="display:none"><label>Leave blank<input name="website" tabindex="-1" autocomplete="off"></label></div>
            <label for="name">Name:</label><input type="text" id="name" name="name" required maxlength="120">
            <label for="email">Email:</label><input type="email" id="email" name="email" required maxlength="190">
            <label for="subject">Subject:</label><input type="text" id="subject" name="subject" required maxlength="180">
            <label for="message">Message:</label><textarea id="message" name="message" required minlength="10"></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</main>
<button id="topButton" type="button" aria-label="Scroll to top">↑</button>
<script src="js/script.js"></script>
</body>
</html>
