<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $pdo->prepare('DELETE FROM projects WHERE id = ?')->execute([$id]);
            $message = 'Project deleted.';
        }
    }

    if ($action === 'save') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $technologies = trim($_POST['technologies'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $projectUrl = trim($_POST['project_url'] ?? '');

        if (!filter_var($projectUrl, FILTER_VALIDATE_URL) && $projectUrl !== '') {
            $message = 'Enter a valid project URL or leave it blank.';
        } elseif ($title === '' || $description === '') {
            $message = 'Project title and description are required.';
        } else {
            $imagePath = '';
            if ($id) {
                $oldProject = $pdo->prepare('SELECT image_path FROM projects WHERE id = ?');
                $oldProject->execute([$id]);
                $imagePath = $oldProject->fetchColumn() ?: '';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    $message = 'The project image upload failed.';
                } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
                    $message = 'The project image must be 3 MB or smaller.';
                } else {
                    $imageInfo = getimagesize($_FILES['image']['tmp_name']);
                    $allowedTypes = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];
                    if ($imageInfo === false || !isset($allowedTypes[$imageInfo[2]])) {
                        $message = 'Only real JPG, PNG, or GIF images are allowed.';
                    } else {
                        $fileName = 'project-' . bin2hex(random_bytes(12)) . '.' . $allowedTypes[$imageInfo[2]];
                        $destination = __DIR__ . '/../uploads/' . $fileName;
                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                            $message = 'The project image could not be saved.';
                        } else {
                            $imagePath = 'uploads/' . $fileName;
                        }
                    }
                }
            }

            if ($message === '') {
                if ($id) {
                    $pdo->prepare('UPDATE projects SET title = ?, description = ?, technologies = ?, category = ?, image_path = ?, project_url = ? WHERE id = ?')
                        ->execute([$title, $description, $technologies, $category, $imagePath, $projectUrl, $id]);
                    $message = 'Project updated.';
                } else {
                    $pdo->prepare('INSERT INTO projects (title, description, technologies, category, image_path, project_url) VALUES (?, ?, ?, ?, ?, ?)')
                        ->execute([$title, $description, $technologies, $category, $imagePath, $projectUrl]);
                    $message = 'Project added.';
                }
            }
        }
    }
}

$projects = $pdo->query('SELECT * FROM projects ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-area">
<?php require __DIR__ . '/navigation.php'; ?>
<main class="admin-main">
    <div class="admin-page-heading">
        <div>
            <h1>Projects</h1>
            <p>Create and manage the projects displayed on your portfolio.</p>
        </div>
    </div>

    <?php if ($message !== ''): ?><p class="admin-alert" role="status"><?= escape($message) ?></p><?php endif; ?>

    <section class="admin-card">
        <h2>Add a project</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="save">
            <div class="admin-form-grid">
                <p><label>Title<br><input name="title" required maxlength="160" placeholder="Project title"></label></p>
                <p><label>Category<br><input name="category" maxlength="80" placeholder="Web, animation, or networking"></label></p>
                <p><label>Project URL<br><input name="project_url" type="url" placeholder="https://example.com"></label></p>
            </div>
            <p><label>Description<br><textarea name="description" required placeholder="Describe the project and its purpose"></textarea></label></p>
            <p><label>Technologies<br><input name="technologies" maxlength="255" placeholder="PHP, MySQL, JavaScript"></label></p>
            <p><label>Project image (JPG, PNG, or GIF, 3 MB max)<br><input name="image" type="file" accept="image/jpeg,image/png,image/gif"></label></p>
            <button type="submit">Add project</button>
        </form>
    </section>

    <section class="admin-list-section">
        <h2>Current projects <span class="admin-count"><?= count($projects) ?></span></h2>
        <?php foreach ($projects as $project): ?>
            <article class="admin-card admin-project-card">
                <?php if (!empty($project['image_path'])): ?>
                    <img class="admin-preview" src="../<?= escape($project['image_path']) ?>" alt="<?= escape($project['title']) ?>">
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
                    <div class="admin-form-grid">
                        <p><label>Title<br><input name="title" value="<?= escape($project['title']) ?>" required maxlength="160"></label></p>
                        <p><label>Category<br><input name="category" value="<?= escape($project['category']) ?>" maxlength="80"></label></p>
                        <p><label>Project URL<br><input name="project_url" type="url" value="<?= escape($project['project_url']) ?>"></label></p>
                    </div>
                    <p><label>Description<br><textarea name="description" required><?= escape($project['description']) ?></textarea></label></p>
                    <p><label>Technologies<br><input name="technologies" value="<?= escape($project['technologies']) ?>" maxlength="255"></label></p>
                    <p><label>Replace image<br><input name="image" type="file" accept="image/jpeg,image/png,image/gif"></label></p>
                    <button type="submit">Save changes</button>
                </form>

                <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this project?');">
                    <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
                    <button type="submit" class="admin-danger-button">Delete project</button>
                </form>
            </article>
        <?php endforeach; ?>

        <?php if (!$projects): ?>
            <div class="admin-card"><p class="admin-muted">No projects added yet.</p></div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
