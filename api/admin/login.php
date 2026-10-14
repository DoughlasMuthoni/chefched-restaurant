<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_post();
start_admin_session();

$data = read_json_or_post();
$username = trimmed($data, 'username', 50);
$password = (string) ($data['password'] ?? '');

if ($username === '' || $password === '') {
    json_response(['status' => 'error', 'message' => 'Username and password are required.'], 422);
}

$pdo = db();
$stmt = $pdo->prepare('SELECT id, password_hash FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    json_response(['status' => 'error', 'message' => 'Incorrect username or password.'], 401);
}

session_regenerate_id(true);
$_SESSION['admin_id'] = (int) $user['id'];
$_SESSION['admin_username'] = $username;

json_response(['status' => 'success', 'username' => $username]);
