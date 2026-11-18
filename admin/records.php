<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$sections = [
    'education' => [
        'title' => 'Education',
        'table' => 'education',
        'fields' => [
            'school' => 'School',
            'qualification' => 'Qualification',
            'details' => 'Details',
            'start_date' => 'Start date',
            'end_date' => 'End date'
        ]
    ],
    'experience' => [
        'title' => 'Experience',
        'table' => 'experience',
        'fields' => [
            'position' => 'Position',
            'organization' => 'Organization',
            'details' => 'Details',
            'start_date' => 'Start date',
            'end_date' => 'End date'
        ]
    ],
    'achievements' => [
        'title' => 'Achievements',
        'table' => 'achievements',
        'fields' => [
            'title' => 'Title',
            'organization' => 'Organization',
            'details' => 'Details',
            'achievement_date' => 'Date'
        ]
    ]
];

$sectionName = $_GET['type'] ?? '';
if (!isset($sections[$sectionName])) {
    http_response_code(404);
    exit('Section not found.');
}
$section = $sections[$sectionName];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $action = $_POST['action'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($action === 'delete' && $id) {
        $statement = $pdo->prepare('DELETE FROM ' . $section['table'] . ' WHERE id = ?');
        $statement->execute([$id]);
        $message = 'Record deleted.';
    }

    if ($action === 'save') {
        $values = [];
        foreach ($section['fields'] as $field => $label) {
            $values[$field] = trim($_POST[$field] ?? '');
        }

        $requiredField = array_key_first($section['fields']);
        if ($values[$requiredField] === '') {
            $message = 'Please fill in the required first field.';
        } else {
            if ($id) {
                $setFields = [];
                foreach (array_keys($values) as $field) {
                    $setFields[] = $field . ' = ?';
                }
                $statement = $pdo->prepare('UPDATE ' . $section['table'] . ' SET ' . implode(', ', $setFields) . ' WHERE id = ?');
                $statement->execute(array_merge(array_values($values), [$id]));
                $message = 'Record updated.';
            } else {
                $columns = implode(', ', array_keys($values));
                $placeholders = implode(', ', array_fill(0, count($values), '?'));
                $statement = $pdo->prepare('INSERT INTO ' . $section['table'] . ' (' . $columns . ') VALUES (' . $placeholders . ')');
                $statement->execute(array_values($values));
                $message = 'Record added.';
            }
        }
    }
}

$records = $pdo->query('SELECT * FROM ' . $section['table'] . ' ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= escape($section['title']) ?> | Admin</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<main>
    <h1>Manage <?= escape($section['title']) ?></h1>
    <p><a href="dashboard.php">Back to dashboard</a></p>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <h2>Add Record</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
        <input type="hidden" name="action" value="save">
        <?php foreach ($section['fields'] as $field => $label): ?>
            <p><label><?= escape($label) ?><br>
                <?php if ($field === 'details'): ?>
                    <textarea name="<?= escape($field) ?>"></textarea>
                <?php else: ?>
                    <input name="<?= escape($field) ?>" maxlength="190" <?= array_key_first($section['fields']) === $field ? 'required' : '' ?>>
                <?php endif; ?>
            </label></p>
        <?php endforeach; ?>
        <button type="submit">Add</button>
    </form>
    <h2>Existing Records</h2>
    <?php foreach ($records as $record): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) $record['id'] ?>">
            <?php foreach ($section['fields'] as $field => $label): ?>
                <p><label><?= escape($label) ?><br>
                    <?php if ($field === 'details'): ?>
                        <textarea name="<?= escape($field) ?>"><?= escape($record[$field]) ?></textarea>
                    <?php else: ?>
                        <input name="<?= escape($field) ?>" value="<?= escape($record[$field]) ?>" maxlength="190" <?= array_key_first($section['fields']) === $field ? 'required' : '' ?>>
                    <?php endif; ?>
                </label></p>
            <?php endforeach; ?>
            <button type="submit">Update</button>
        </form>
        <form method="post" onsubmit="return confirm('Delete this record?');">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $record['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    <?php endforeach; ?>
</main>
</body>
</html>
