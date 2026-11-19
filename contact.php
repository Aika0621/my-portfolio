<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/security.php';

$statusMessage = '';
$formValues = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

$profile = $pdo->query('SELECT name, email FROM profile ORDER BY id LIMIT 1')->fetch();
if (!$profile) {
    $profile = ['name' => 'Aika', 'email' => ''];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    foreach ($formValues as $field => $unused) {
        $formValues[$field] = trim($_POST[$field] ?? '');
    }

    $validName = strlen($formValues['name']) >= 2 && strlen($formValues['name']) <= 120;
    $validEmail = filter_var($formValues['email'], FILTER_VALIDATE_EMAIL) !== false;
    $validSubject = strlen($formValues['subject']) >= 2 && strlen($formValues['subject']) <= 180;
    $validMessage = strlen($formValues['message']) >= 10 && strlen($formValues['message']) <= 5000;

    if (!$validName || !$validEmail || !$validSubject || !$validMessage) {
        $statusMessage = 'Please complete all fields with valid information.';
    } elseif (trim($_POST['website'] ?? '') !== '') {
        $statusMessage = 'Thank you. Your message has been sent.';
        $formValues = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
    } else {
        try {
            $statement = $pdo->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
            $statement->execute([
                $formValues['name'],
                $formValues['email'],
                $formValues['subject'],
                $formValues['message']
            ]);

            $statusMessage = 'Thank you. Your message has been sent.';
            $formValues = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        } catch (PDOException $error) {
            $statusMessage = 'The message could not be saved. Start MySQL and import database/portfolio.sql.';
        }
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
    <nav aria-label="Main navigation">
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
        <p id="formMessage" role="status" aria-live="polite"><?= escape($statusMessage) ?></p>

        <form id="contactForm" method="post" action="contact.php">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <div style="display:none"><label>Leave blank<input name="website" tabindex="-1" autocomplete="off"></label></div>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required minlength="2" maxlength="120" value="<?= escape($formValues['name']) ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required maxlength="190" value="<?= escape($formValues['email']) ?>">

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required minlength="2" maxlength="180" value="<?= escape($formValues['subject']) ?>">

            <label for="message">Message:</label>
            <textarea id="message" name="message" required minlength="10" maxlength="5000"><?= escape($formValues['message']) ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</main>

<button id="topButton" type="button" aria-label="Scroll to top">↑</button>
<script src="js/script.js"></script>
</body>
</html>
