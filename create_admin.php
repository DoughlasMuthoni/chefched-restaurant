<?php
/**
 * Run this ONCE in the browser (e.g. https://yourdomain.com/create_admin.php)
 * to create the first dashboard login, then DELETE this file from the server.
 *
 * Usage: visit it with ?username=...&password=... the first time; it refuses
 * to run again once an admin_users row already exists, so re-running is safe
 * even if you forget to delete it right away — but delete it anyway.
 */

require_once __DIR__ . '/api/db.php';

header('Content-Type: text/plain');

$pdo = db();
$existing = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
if ($existing > 0) {
    echo "An admin user already exists. Delete this file — it will not create another.\n";
    exit;
}

$username = trim((string) ($_GET['username'] ?? ''));
$password = (string) ($_GET['password'] ?? '');

if ($username === '' || strlen($password) < 8) {
    echo "Visit this page as:\n";
    echo "  create_admin.php?username=YOURNAME&password=YOURPASSWORD\n";
    echo "(password must be at least 8 characters)\n";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)')->execute([$username, $hash]);

echo "Admin user '{$username}' created. Now DELETE this file from the server.\n";
