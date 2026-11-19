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
        $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = ?')->execute([$id]);
        $message = 'Message marked as read.';
    }
    if ($id && $action === 'unread') {
        $pdo->prepare('UPDATE messages SET is_read = 0 WHERE id = ?')->execute([$id]);
        $message = 'Message marked as unread.';
    }
    if ($id && $action === 'delete') {
        $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
        $message = 'Message deleted.';
    }
}

$messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
$unreadCount = 0;
foreach ($messages as $item) {
    if (!$item['is_read']) {
        $unreadCount++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Messages | Admin</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="admin.css"></head>
<body class="admin-area">
<?php require __DIR__ . '/navigation.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading"><div><h1>Contact messages</h1><p><?= count($messages) ?> total · <?= $unreadCount ?> unread</p></div></div>
    <?php if ($message !== ''): ?><p class="admin-alert" role="status"><?= escape($message) ?></p><?php endif; ?>
    <?php foreach ($messages as $item): ?>
        <article class="admin-card admin-message-card<?= $item['is_read'] ? '' : ' admin-message-unread' ?>">
            <div class="admin-message-heading">
                <div><span class="admin-message-status <?= $item['is_read'] ? 'read' : 'unread' ?>"><?= $item['is_read'] ? 'Read' : 'Unread' ?></span><h2><?= escape($item['subject']) ?></h2></div>
                <span class="admin-message-date"><?= escape($item['created_at']) ?></span>
            </div>
            <p class="admin-muted">From <?= escape($item['name']) ?> · <a href="mailto:<?= escape($item['email']) ?>"><?= escape($item['email']) ?></a></p>
            <p class="admin-message-text"><?= nl2br(escape($item['message'])) ?></p>
            <form method="post" class="admin-message-actions">
                <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                <button type="submit" name="action" value="read">Mark read</button>
                <button type="submit" name="action" value="unread" class="admin-secondary-button">Mark unread</button>
                <button type="submit" name="action" value="delete" class="admin-danger-button" onclick="return confirm('Delete this message?');">Delete</button>
            </form>
        </article>
    <?php endforeach; ?>
    <?php if (!$messages): ?><div class="admin-card"><p class="admin-muted">No contact messages yet.</p></div><?php endif; ?>
</main>
</body>
</html>
