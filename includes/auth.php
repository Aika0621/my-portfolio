<?php

require_once __DIR__ . '/security.php';

function requireAdmin()
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}
