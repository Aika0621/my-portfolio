<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $action = $_POST['action'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id && $action === 'read') {
        $statement = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = ?');
        $statement->execute([$id]);
        $message = 'Message marked as read.';
    }

    if ($id && $action === 'unread') {
        $statement = $pdo->prepare('UPDATE messages SET is_read = 0 WHERE id = ?');
        $statement->execute([$id]);
        $message = 'Message marked as unread.';
    }

    if ($id && $action === 'delete') {
        $statement = $pdo->prepare('DELETE FROM messages WHERE id = ?');
        $statement->execute([$id]);
        $message = 'Message deleted.';
    }
}

$messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Messages | Admin</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<main>
    <h1>Messages</h1>
    <p><a href="dashboard.php">Back to dashboard</a></p>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <?php foreach ($messages as $item): ?>
        <article>
            <h2><?= escape($item['subject']) ?></h2>
            <p>From: <?= escape($item['name']) ?> (<?= escape($item['email']) ?>)</p>
            <p><?= escape($item['message']) ?></p>
            <p>Status: <?= $item['is_read'] ? 'Read' : 'Unread' ?></p>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                <button type="submit" name="action" value="read">Mark Read</button>
                <button type="submit" name="action" value="unread">Mark Unread</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Delete this message?');">Delete</button>
            </form>
        </article>
    <?php endforeach; ?>
</main>
</body>
</html>
