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
            $statement = $pdo->prepare('DELETE FROM projects WHERE id = ?');
            $statement->execute([$id]);
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
                $statement = $pdo->prepare('SELECT image_path FROM projects WHERE id = ?');
                $statement->execute([$id]);
                $oldProject = $statement->fetch();
                $imagePath = $oldProject['image_path'] ?? '';
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
                    $statement = $pdo->prepare('UPDATE projects SET title = ?, description = ?, technologies = ?, category = ?, image_path = ?, project_url = ? WHERE id = ?');
                    $statement->execute([$title, $description, $technologies, $category, $imagePath, $projectUrl, $id]);
                    $message = 'Project updated.';
                } else {
                    $statement = $pdo->prepare('INSERT INTO projects (title, description, technologies, category, image_path, project_url) VALUES (?, ?, ?, ?, ?, ?)');
                    $statement->execute([$title, $description, $technologies, $category, $imagePath, $projectUrl]);
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
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Projects | Admin</title><link rel="stylesheet" href="../css/style.css"></head>
<body>
<main>
    <h1>Manage Projects</h1>
    <p><a href="dashboard.php">Back to dashboard</a></p>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <h2>Add Project</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
        <input type="hidden" name="action" value="save">
        <p><input name="title" placeholder="Project title" required maxlength="160"></p>
        <p><textarea name="description" placeholder="Description" required></textarea></p>
        <p><input name="technologies" placeholder="Technologies" maxlength="255"></p>
        <p><input name="category" placeholder="Category: web, animation, or networking" maxlength="80"></p>
        <p><input name="project_url" type="url" placeholder="Project URL"></p>
        <p><input name="image" type="file" accept="image/jpeg,image/png,image/gif"></p>
        <button type="submit">Add Project</button>
    </form>
    <h2>Existing Projects</h2>
    <?php foreach ($projects as $project): ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
            <p><input name="title" value="<?= escape($project['title']) ?>" required maxlength="160"></p>
            <p><textarea name="description" required><?= escape($project['description']) ?></textarea></p>
            <p><input name="technologies" value="<?= escape($project['technologies']) ?>" maxlength="255"></p>
            <p><input name="category" value="<?= escape($project['category']) ?>" maxlength="80"></p>
            <p><input name="project_url" type="url" value="<?= escape($project['project_url']) ?>"></p>
            <p><input name="image" type="file" accept="image/jpeg,image/png,image/gif"></p>
            <button type="submit">Update</button>
        </form>
        <form method="post" onsubmit="return confirm('Delete this project?');">
            <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $project['id'] ?>">
            <button type="submit">Delete</button>
        </form>
    <?php endforeach; ?>
</main>
</body>
</html>
