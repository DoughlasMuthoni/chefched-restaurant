<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $rows = $pdo->query('SELECT * FROM blog_posts ORDER BY published_at DESC, id DESC')->fetchAll();
    json_response(['status' => 'success', 'data' => $rows]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_json_or_post();
    $title = trimmed($data, 'title', 200);
    if ($title === '') {
        json_response(['status' => 'error', 'message' => 'Title is required.'], 422);
    }

    $requestedSlug = trimmed($data, 'slug', 220);
    $baseSlug = slugify($requestedSlug !== '' ? $requestedSlug : $title);
    $slug = unique_slug($pdo, 'blog_posts', $baseSlug);

    $publishedAt = trimmed($data, 'published_at', 10);
    if ($publishedAt === '') {
        $publishedAt = date('Y-m-d');
    }

    $stmt = $pdo->prepare(
        'INSERT INTO blog_posts (title, slug, excerpt, body, category, cover_image_path, published_at)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $title,
        $slug,
        trimmed($data, 'excerpt', 500),
        (string) ($data['body'] ?? ''), // deliberately raw HTML — see note in blog-detail.php
        trimmed($data, 'category', 80),
        trimmed($data, 'cover_image_path', 255),
        $publishedAt,
    ]);

    json_response(['status' => 'success', 'id' => (int) $pdo->lastInsertId(), 'slug' => $slug]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_response(['status' => 'error', 'message' => 'Invalid id.'], 422);
    }
    $data = read_json_or_post();

    $title = trimmed($data, 'title', 200);
    if ($title === '') {
        json_response(['status' => 'error', 'message' => 'Title is required.'], 422);
    }

    $publishedAt = trimmed($data, 'published_at', 10);
    if ($publishedAt === '') {
        $publishedAt = date('Y-m-d');
    }

    // Slug is intentionally immutable after creation so /blog/<slug> links never break.
    $stmt = $pdo->prepare(
        'UPDATE blog_posts SET title = ?, excerpt = ?, body = ?, category = ?, cover_image_path = ?, published_at = ? WHERE id = ?'
    );
    $stmt->execute([
        $title,
        trimmed($data, 'excerpt', 500),
        (string) ($data['body'] ?? ''),
        trimmed($data, 'category', 80),
        trimmed($data, 'cover_image_path', 255),
        $publishedAt,
        $id,
    ]);

    json_response(['status' => 'success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
        json_response(['status' => 'error', 'message' => 'Invalid id.'], 422);
    }
    $pdo->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
    json_response(['status' => 'success']);
}

json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
