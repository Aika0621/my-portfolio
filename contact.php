<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/security.php';

$message = '';
$profile = $pdo->query('SELECT name, email FROM profile ORDER BY id LIMIT 1')->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $contactMessage = trim($_POST['message'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($name) < 2 || strlen($subject) < 2 || strlen($contactMessage) < 10) {
        $message = 'Please complete all fields with valid information.';
    } else {
        $honeypot = trim($_POST['website'] ?? '');

        if ($honeypot !== '') {
            $message = 'Your message could not be sent.';
        } else {
            $statement = $pdo->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
            $statement->execute([$name, $email, $subject, $contactMessage]);
            $message = 'Thank you. Your message has been sent.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Contact | Portfolio</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>Contact</h1><nav><a href="index.php">Home</a><a href="projects.php">Projects</a><a href="contact.php">Contact</a></nav></header>
<main>
    <h2>Send a Message</h2>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
        <div style="display:none"><label>Leave blank<input name="website" tabindex="-1" autocomplete="off"></label></div>
        <p><label>Name<br><input name="name" required maxlength="120"></label></p>
        <p><label>Email<br><input name="email" type="email" required maxlength="190"></label></p>
        <p><label>Subject<br><input name="subject" required maxlength="180"></label></p>
        <p><label>Message<br><textarea name="message" required minlength="10"></textarea></label></p>
        <button type="submit">Send Message</button>
    </form>
    <?php if (!empty($profile['email'])): ?><p>Email: <?= escape($profile['email']) ?></p><?php endif; ?>
</main>
<script src="js/script.js"></script>
</body>
</html>
