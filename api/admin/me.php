<?php
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

start_admin_session();
$id = current_admin_id();

if ($id === null) {
    json_response(['status' => 'error', 'authenticated' => false], 401);
}

json_response(['status' => 'success', 'authenticated' => true, 'username' => $_SESSION['admin_username'] ?? null]);
