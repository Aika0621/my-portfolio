<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $action = $_POST['action'] ?? '';
    if ($action === 'add' || $action === 'update') {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]);
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($name === '' || $category === '' || $level === false || ($action === 'update' && !$id)) {
            $message = 'Please enter a name, category, and level from 0 to 100.';
        } elseif ($action === 'add') {
            $pdo->prepare('INSERT INTO skills (name, category, level) VALUES (?, ?, ?)')->execute([$name, $category, $level]);
            $message = 'Skill added.';
        } else {
            $pdo->prepare('UPDATE skills SET name = ?, category = ?, level = ? WHERE id = ?')->execute([$name, $category, $level, $id]);
            $message = 'Skill updated.';
        }
    }
    if ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) { $pdo->prepare('DELETE FROM skills WHERE id = ?')->execute([$id]); $message = 'Skill deleted.'; }
    }
}
$skills = $pdo->query('SELECT * FROM skills ORDER BY category, name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Skills | Admin</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="admin.css"></head>
<body class="admin-area">
<?php require __DIR__ . '/navigation.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading"><div><h1>Skills</h1><p>Add and organize the skills shown on your portfolio.</p></div></div>
    <?php if ($message !== ''): ?><p class="admin-alert" role="status"><?= escape($message) ?></p><?php endif; ?>
    <section class="admin-card"><h2>Add a skill</h2><form method="post" class="admin-form-grid">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="add">
        <p><label>Skill name<br><input name="name" required maxlength="100"></label></p><p><label>Category<br><input name="category" required maxlength="100"></label></p><p><label>Level (0–100)<br><input name="level" type="number" min="0" max="100" value="50" required></label></p><button type="submit">Add skill</button>
    </form></section>
    <section class="admin-list-section"><h2>Current skills <span class="admin-count"><?= count($skills) ?></span></h2>
    <?php foreach ($skills as $skill): ?><article class="admin-card admin-list-card"><form method="post" class="admin-form-grid">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int) $skill['id'] ?>">
        <p><label>Skill name<br><input name="name" value="<?= escape($skill['name']) ?>" required maxlength="100"></label></p><p><label>Category<br><input name="category" value="<?= escape($skill['category']) ?>" required maxlength="100"></label></p><p><label>Level<br><input name="level" type="number" min="0" max="100" value="<?= (int) $skill['level'] ?>" required></label></p><button type="submit">Save changes</button>
    </form><form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this skill?');"><input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $skill['id'] ?>"><button type="submit" class="admin-danger-button">Delete</button></form></article><?php endforeach; ?>
    <?php if (!$skills): ?><div class="admin-card"><p class="admin-muted">No skills added yet.</p></div><?php endif; ?></section>
</main></body></html>
