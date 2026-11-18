<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$message = '';
$profile = $pdo->query('SELECT * FROM profile ORDER BY id LIMIT 1')->fetch();

if (!$profile) {
    $profile = [
        'name' => '',
        'introduction' => '',
        'biography' => '',
        'email' => '',
        'phone' => '',
        'location' => '',
        'image_path' => ''
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrfToken();

    $name = trim($_POST['name'] ?? '');
    $introduction = trim($_POST['introduction'] ?? '');
    $biography = trim($_POST['biography'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if ($name === '' || $introduction === '' || $biography === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please complete the required fields with a valid email address.';
    } else {
        $imagePath = $profile['image_path'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $message = 'The profile image upload failed.';
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $message = 'The profile image must be 2 MB or smaller.';
            } else {
                $imageInfo = getimagesize($_FILES['image']['tmp_name']);
                $allowedTypes = [
                    IMAGETYPE_JPEG => 'jpg',
                    IMAGETYPE_PNG => 'png',
                    IMAGETYPE_GIF => 'gif'
                ];

                if ($imageInfo === false || !isset($allowedTypes[$imageInfo[2]])) {
                    $message = 'Only real JPG, PNG, or GIF images are allowed.';
                } else {
                    $extension = $allowedTypes[$imageInfo[2]];
                    $fileName = 'profile-' . bin2hex(random_bytes(12)) . '.' . $extension;
                    $destination = __DIR__ . '/../uploads/' . $fileName;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                        $message = 'The profile image could not be saved.';
                    } else {
                        $imagePath = 'uploads/' . $fileName;
                    }
                }
            }
        }

        if ($message === '') {
            $data = [$name, $introduction, $biography, $email, $phone, $location, $imagePath];

            if ($profile['id']) {
                $statement = $pdo->prepare('UPDATE profile SET name = ?, introduction = ?, biography = ?, email = ?, phone = ?, location = ?, image_path = ? WHERE id = ?');
                $data[] = $profile['id'];
                $statement->execute($data);
            } else {
                $statement = $pdo->prepare('INSERT INTO profile (name, introduction, biography, email, phone, location, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $statement->execute($data);
            }

            $message = 'Profile saved.';
            $profile = $pdo->query('SELECT * FROM profile ORDER BY id LIMIT 1')->fetch();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main>
    <h1>Manage Profile</h1>
    <p><a href="dashboard.php">Back to dashboard</a></p>
    <?php if ($message !== ''): ?><p><?= escape($message) ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= escape(csrfToken()) ?>">
        <p><label>Name<br><input name="name" value="<?= escape($profile['name']) ?>" required maxlength="120"></label></p>
        <p><label>Introduction<br><input name="introduction" value="<?= escape($profile['introduction']) ?>" required maxlength="255"></label></p>
        <p><label>Biography<br><textarea name="biography" required><?= escape($profile['biography']) ?></textarea></label></p>
        <p><label>Email<br><input name="email" type="email" value="<?= escape($profile['email']) ?>" required maxlength="190"></label></p>
        <p><label>Phone<br><input name="phone" value="<?= escape($profile['phone']) ?>" maxlength="40"></label></p>
        <p><label>Location<br><input name="location" value="<?= escape($profile['location']) ?>" maxlength="120"></label></p>
        <p><label>Profile Image (JPG, PNG, or GIF, 2 MB max)<br><input name="image" type="file" accept="image/jpeg,image/png,image/gif"></label></p>
        <button type="submit">Save Profile</button>
    </form>
</main>
</body>
</html>
