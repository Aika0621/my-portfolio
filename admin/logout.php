<?php

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

checkCsrfToken();
$_SESSION = [];
session_regenerate_id(true);
session_destroy();
header('Location: login.php');
exit;
