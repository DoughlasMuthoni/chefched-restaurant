<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();
$pdo = db();

/** Normalizes a free-typed "Private Dining, Chef Portraits" into the
 *  space-separated CSS-class-safe slug string the public gallery page reads
 *  (e.g. "private-dining chef-portraits"). */
function normalize_categories(string $raw): string {
    $parts = array_filter(array_map('trim', explode(',', $raw)));
    $slugs = array_map('slugify', $parts);
    return implode(' ', array_unique($slugs));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $rows = $pdo->query('SELECT * FROM gallery_items ORDER BY sort_order ASC, id ASC')->fetchAll();
    json_response(['status' => 'success', 'data' => $rows]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_or_post();
    $imagePath = trimmed($data, 'image_path', 255);
    if ($imagePath === '') {
        json_response(['status' => 'error', 'message' => 'An image is required.'], 422);
    }

    $maxOrder = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM gallery_items')->fetchColumn();

    $stmt = $pdo->prepare('INSERT INTO gallery_items (caption, image_path, categories, sort_order) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        trimmed($data, 'caption', 160),
        $imagePath,
        normalize_categories(trimmed($data, 'categories', 255)),
        $maxOrder + 1,
    ]);

    json_response(['status' => 'success', 'id' => (int) $pdo->lastInsertId()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_response(['status' => 'error', 'message' => 'Invalid id.'], 422);
    }
    $data = read_json_or_post();

    if (isset($data['move']) && in_array($data['move'], ['up', 'down'], true)) {
        $current = $pdo->prepare('SELECT id, sort_order FROM gallery_items WHERE id = ?');
        $current->execute([$id]);
        $row = $current->fetch();
        if (!$row) {
            json_response(['status' => 'error', 'message' => 'Not found.'], 404);
        }
        $op = $data['move'] === 'up' ? '<' : '>';
        $order = $data['move'] === 'up' ? 'DESC' : 'ASC';
        $neighborStmt = $pdo->prepare("SELECT id, sort_order FROM gallery_items WHERE sort_order {$op} ? ORDER BY sort_order {$order} LIMIT 1");
        $neighborStmt->execute([$row['sort_order']]);
        $neighbor = $neighborStmt->fetch();
        if ($neighbor) {
            $pdo->prepare('UPDATE gallery_items SET sort_order = ? WHERE id = ?')->execute([$neighbor['sort_order'], $row['id']]);
            $pdo->prepare('UPDATE gallery_items SET sort_order = ? WHERE id = ?')->execute([$row['sort_order'], $neighbor['id']]);
        }
        json_response(['status' => 'success']);
    }

    $imagePath = trimmed($data, 'image_path', 255);
    if ($imagePath === '') {
        json_response(['status' => 'error', 'message' => 'An image is required.'], 422);
    }

    $stmt = $pdo->prepare('UPDATE gallery_items SET caption = ?, image_path = ?, categories = ? WHERE id = ?');
    $stmt->execute([
        trimmed($data, 'caption', 160),
        $imagePath,
        normalize_categories(trimmed($data, 'categories', 255)),
        $id,
    ]);

    json_response(['status' => 'success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_response(['status' => 'error', 'message' => 'Invalid id.'], 422);
    }
    $pdo->prepare('DELETE FROM gallery_items WHERE id = ?')->execute([$id]);
    json_response(['status' => 'success']);
}

json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
