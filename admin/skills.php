<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);

        if ($name === '' || $category === '' || $level === false) {
            $message = 'Please enter a name, category, and level from 0 to 100.';
        } else {
            $statement = $pdo->prepare('INSERT INTO skills (name, category, level) VALUES (?, ?, ?)');
            $statement->execute([$name, $category, $level]);
            $message = 'Skill added.';
        }
    }

    if ($action === 'update') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);

        if (!$id || $name === '' || $category === '' || $level === false) {
            $message = 'The skill could not be updated.';
        } else {
            $statement = $pdo->prepare('UPDATE skills SET name = ?, category = ?, level = ? WHERE id = ?');
            $statement->execute([$name, $category, $level, $id]);
            $message = 'Skill updated.';
        }
    }

    if ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $statement = $pdo->prepare('DELETE FROM skills WHERE id = ?');
            $statement->execute([$id]);
            $message = 'Skill deleted.';
        }
    }
}

$skills = $pdo->query('SELECT * FROM skills ORDER BY category, name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Skills | Admin</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<main>
    <h1>Manage Skills</h1>
    <p><a href="dashboard.php">Back to dashboard</a></p>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <h2>Add Skill</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
        <input type="hidden" name="action" value="add">
        <p><input name="name" placeholder="Skill name" required maxlength="100"></p>
        <p><input name="category" placeholder="Category" required maxlength="100"></p>
        <p><input name="level" type="number" min="0" max="100" value="50" required></p>
        <button type="submit">Add Skill</button>
    </form>
    <h2>Existing Skills</h2>
    <?php foreach ($skills as $skill): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= (int) $skill['id'] ?>">
            <input name="name" value="<?= escape($skill['name']) ?>" required maxlength="100">
            <input name="category" value="<?= escape($skill['category']) ?>" required maxlength="100">
            <input name="level" type="number" min="0" max="100" value="<?= (int) $skill['level'] ?>" required>
            <button type="submit">Update</button>
        </form>
        <form method="post" onsubmit="return confirm('Delete this skill?');">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $skill['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    <?php endforeach; ?>
</main>
</body>
</html>
