<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $rows = $pdo->query('SELECT * FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
    json_response(['status' => 'success', 'data' => $rows]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_or_post();
    $title = trimmed($data, 'title', 160);
    if ($title === '') {
        json_response(['status' => 'error', 'message' => 'Title is required.'], 422);
    }

    $maxOrder = (int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM services')->fetchColumn();

    $stmt = $pdo->prepare(
        'INSERT INTO services (eyebrow, title, description, sub_line, cta_label, cta_href, image_path, sort_order)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        trimmed($data, 'eyebrow', 100),
        $title,
        trimmed($data, 'description', 2000),
        trimmed($data, 'sub_line', 255),
        trimmed($data, 'cta_label', 80),
        trimmed($data, 'cta_href', 160),
        trimmed($data, 'image_path', 255),
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

    // Move: swap this row's sort_order with its neighbor in the given direction.
    if (isset($data['move']) && in_array($data['move'], ['up', 'down'], true)) {
        $current = $pdo->prepare('SELECT id, sort_order FROM services WHERE id = ?');
        $current->execute([$id]);
        $row = $current->fetch();
        if (!$row) {
            json_response(['status' => 'error', 'message' => 'Not found.'], 404);
        }
        $op = $data['move'] === 'up' ? '<' : '>';
        $order = $data['move'] === 'up' ? 'DESC' : 'ASC';
        $neighborStmt = $pdo->prepare("SELECT id, sort_order FROM services WHERE sort_order {$op} ? ORDER BY sort_order {$order} LIMIT 1");
        $neighborStmt->execute([$row['sort_order']]);
        $neighbor = $neighborStmt->fetch();
        if ($neighbor) {
            $pdo->prepare('UPDATE services SET sort_order = ? WHERE id = ?')->execute([$neighbor['sort_order'], $row['id']]);
            $pdo->prepare('UPDATE services SET sort_order = ? WHERE id = ?')->execute([$row['sort_order'], $neighbor['id']]);
        }
        json_response(['status' => 'success']);
    }

    $title = trimmed($data, 'title', 160);
    if ($title === '') {
        json_response(['status' => 'error', 'message' => 'Title is required.'], 422);
    }

    $stmt = $pdo->prepare(
        'UPDATE services SET eyebrow = ?, title = ?, description = ?, sub_line = ?, cta_label = ?, cta_href = ?, image_path = ? WHERE id = ?'
    );
    $stmt->execute([
        trimmed($data, 'eyebrow', 100),
        $title,
        trimmed($data, 'description', 2000),
        trimmed($data, 'sub_line', 255),
        trimmed($data, 'cta_label', 80),
        trimmed($data, 'cta_href', 160),
        trimmed($data, 'image_path', 255),
        $id,
    ]);

    json_response(['status' => 'success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_response(['status' => 'error', 'message' => 'Invalid id.'], 422);
    }
    $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([$id]);
    json_response(['status' => 'success']);
}

json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
