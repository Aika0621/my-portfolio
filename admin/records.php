<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$sections = [
    'education' => ['title' => 'Education', 'table' => 'education', 'fields' => ['school' => 'School', 'qualification' => 'Qualification', 'details' => 'Details', 'start_date' => 'Start date', 'end_date' => 'End date']],
    'experience' => ['title' => 'Experience', 'table' => 'experience', 'fields' => ['position' => 'Position', 'organization' => 'Organization', 'details' => 'Details', 'start_date' => 'Start date', 'end_date' => 'End date']],
    'achievements' => ['title' => 'Achievements', 'table' => 'achievements', 'fields' => ['title' => 'Title', 'organization' => 'Organization', 'details' => 'Details', 'achievement_date' => 'Date']]
];
$sectionName = $_GET['type'] ?? '';
if (!isset($sections[$sectionName])) { http_response_code(404); exit('Section not found.'); }
$section = $sections[$sectionName];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $action = $_POST['action'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($action === 'delete' && $id) { $pdo->prepare('DELETE FROM ' . $section['table'] . ' WHERE id = ?')->execute([$id]); $message = 'Record deleted.'; }
    if ($action === 'save') {
        $values = []; foreach (array_keys($section['fields']) as $field) { $values[$field] = trim($_POST[$field] ?? ''); }
        $requiredField = array_key_first($section['fields']);
        if ($values[$requiredField] === '') { $message = 'Please complete the required field.'; }
        elseif ($id) { $set = []; foreach (array_keys($values) as $field) { $set[] = $field . ' = ?'; } $pdo->prepare('UPDATE ' . $section['table'] . ' SET ' . implode(', ', $set) . ' WHERE id = ?')->execute(array_merge(array_values($values), [$id])); $message = 'Record updated.'; }
        else { $columns = implode(', ', array_keys($values)); $placeholders = implode(', ', array_fill(0, count($values), '?')); $pdo->prepare('INSERT INTO ' . $section['table'] . ' (' . $columns . ') VALUES (' . $placeholders . ')')->execute(array_values($values)); $message = 'Record added.'; }
    }
}
$records = $pdo->query('SELECT * FROM ' . $section['table'] . ' ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= escape($section['title']) ?> | Admin</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="admin.css"></head>
<body class="admin-area"><?php require __DIR__ . '/navigation.php'; ?><main class="admin-main">
<div class="admin-page-heading"><div><h1><?= escape($section['title']) ?></h1><p>Manage your portfolio <?= escape(strtolower($section['title'])) ?> records.</p></div></div>
<?php if ($message !== ''): ?><p class="admin-alert" role="status"><?= escape($message) ?></p><?php endif; ?>
<section class="admin-card"><h2>Add <?= escape(strtolower($section['title'])) ?></h2><form method="post"><input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="save">
<?php foreach ($section['fields'] as $field => $label): ?><p><label><?= escape($label) ?><br><?php if ($field === 'details'): ?><textarea name="<?= escape($field) ?>"></textarea><?php else: ?><input name="<?= escape($field) ?>" maxlength="190" <?= array_key_first($section['fields']) === $field ? 'required' : '' ?>><?php endif; ?></label></p><?php endforeach; ?><button type="submit">Add record</button></form></section>
<section class="admin-list-section"><h2>Current records <span class="admin-count"><?= count($records) ?></span></h2><?php foreach ($records as $record): ?><article class="admin-card admin-list-card"><form method="post"><input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int) $record['id'] ?>">
<?php foreach ($section['fields'] as $field => $label): ?><p><label><?= escape($label) ?><br><?php if ($field === 'details'): ?><textarea name="<?= escape($field) ?>"><?= escape($record[$field]) ?></textarea><?php else: ?><input name="<?= escape($field) ?>" value="<?= escape($record[$field]) ?>" maxlength="190" <?= array_key_first($section['fields']) === $field ? 'required' : '' ?>><?php endif; ?></label></p><?php endforeach; ?><button type="submit">Save changes</button></form><form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this record?');"><input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $record['id'] ?>"><button type="submit" class="admin-danger-button">Delete</button></form></article><?php endforeach; ?><?php if (!$records): ?><div class="admin-card"><p class="admin-muted">No records added yet.</p></div><?php endif; ?></section>
</main></body></html>
