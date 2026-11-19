<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';
$settings = $pdo->query('SELECT setting_name, setting_value FROM settings')->fetchAll();
$settingValues = [];
foreach ($settings as $setting) {
    $settingValues[$setting['setting_name']] = $setting['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $title = trim($_POST['site_title'] ?? '');
    if ($title === '') {
        $message = 'Website title is required.';
    } else {
        $statement = $pdo->prepare('INSERT INTO settings (setting_name, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        $statement->execute(['site_title', $title]);
        $message = 'Settings saved.';
        $settingValues['site_title'] = $title;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Settings | Admin</title><link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="admin.css"></head>
<body class="admin-area">
<?php require __DIR__ . '/navigation.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading"><div><h1>Website settings</h1><p>Update basic portfolio website information.</p></div></div>
    <section class="admin-card">
        <?php if ($message !== ''): ?><p class="admin-alert" role="status"><?= escape($message) ?></p><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <p><label>Website title<br><input name="site_title" value="<?= escape($settingValues['site_title'] ?? 'Aika | My Portfolio') ?>" required maxlength="100"></label></p>
            <button type="submit">Save settings</button>
        </form>
    </section>
</main>
</body>
</html>
