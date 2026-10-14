<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $status = trim((string) ($_GET['status'] ?? ''));
    $search = trim((string) ($_GET['search'] ?? ''));
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = min(100, max(1, (int) ($_GET['per_page'] ?? 25)));
    $offset = ($page - 1) * $perPage;

    $where = [];
    $params = [];
    if ($status !== '' && $status !== 'all') {
        $where[] = 'status = ?';
        $params[] = $status;
    }
    if ($search !== '') {
        $where[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ?)';
        $like = '%' . $search . '%';
        array_push($params, $like, $like, $like);
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM enquiries {$whereSql}");
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM enquiries {$whereSql} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['status' => 'success', 'data' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $id = (int) ($_GET['id'] ?? 0);
    $data = read_json_or_post();
    $newStatus = trimmed($data, 'status', 20);
    $allowed = ['new', 'contacted', 'booked', 'closed'];

    if ($id <= 0 || !in_array($newStatus, $allowed, true)) {
        json_response(['status' => 'error', 'message' => 'Invalid id or status.'], 422);
    }

    $pdo->prepare('UPDATE enquiries SET status = ? WHERE id = ?')->execute([$newStatus, $id]);
    json_response(['status' => 'success']);
}

json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
