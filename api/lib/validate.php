<?php
/**
 * Shared input validation/sanitization helpers for the public form endpoints.
 */

function read_json_or_post(): array {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
    return $_POST;
}

function trimmed(array $data, string $key, int $maxLen = 500): string {
    $val = isset($data[$key]) ? (string) $data[$key] : '';
    $val = trim($val);
    return mb_substr($val, 0, $maxLen);
}

function is_valid_email(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/** Strip anything that isn't a digit/plus, collapse whitespace — tolerant of
 *  how people actually type phone numbers, not a strict E.164 validator. */
function clean_phone(string $phone): string {
    $phone = preg_replace('/[^\d+]/', '', $phone);
    return $phone ?? '';
}

function client_ip(): string {
    foreach (['HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = trim(explode(',', $_SERVER[$key])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return '0.0.0.0';
}

function json_response(array $payload, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function require_post(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
    }
}

function slugify(string $text): string {
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug !== '' ? $slug : 'post';
}

/** Appends -2, -3, ... until the slug doesn't collide with an existing row
 *  (optionally excluding $excludeId, for updates that keep their own slug). */
function unique_slug(PDO $pdo, string $table, string $base, ?int $excludeId = null): string {
    $slug = $base;
    $i = 2;
    while (true) {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE slug = ?";
        $params = [$slug];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}
