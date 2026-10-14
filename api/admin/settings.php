<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();
$pdo = db();

$allowedKeys = ['contact_info', 'homepage'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $rows = $pdo->query('SELECT content_key, value FROM site_content')->fetchAll();
    $merged = [];
    foreach ($rows as $row) {
        $merged[$row['content_key']] = json_decode($row['value'], true);
    }
    json_response(['status' => 'success', 'data' => $merged]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $key = trim((string) ($_GET['key'] ?? ''));
    if (!in_array($key, $allowedKeys, true)) {
        json_response(['status' => 'error', 'message' => 'Invalid settings key.'], 422);
    }

    $data = read_json_or_post();
    $value = $data['value'] ?? null;
    if (!is_array($value)) {
        json_response(['status' => 'error', 'message' => 'A value object is required.'], 422);
    }

    $json = json_encode($value, JSON_UNESCAPED_UNICODE);
    $stmt = $pdo->prepare(
        'INSERT INTO site_content (content_key, value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE value = VALUES(value)'
    );
    $stmt->execute([$key, $json]);

    json_response(['status' => 'success']);
}

json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
