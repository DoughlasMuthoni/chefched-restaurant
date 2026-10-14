<?php
/**
 * Session-based auth for the admin API. The React dashboard and the PHP API
 * are served from the same origin, so a plain httpOnly session cookie is all
 * that's needed — no JWT, no CORS.
 */

require_once __DIR__ . '/validate.php';

function start_admin_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        ]);
        session_name('chefched_admin_session');
        session_start();
    }
}

function current_admin_id(): ?int {
    start_admin_session();
    return $_SESSION['admin_id'] ?? null;
}

function require_admin(): int {
    $id = current_admin_id();
    if ($id === null) {
        json_response(['status' => 'error', 'message' => 'Not authenticated'], 401);
    }
    return $id;
}
