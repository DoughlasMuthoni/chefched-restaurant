<?php
/**
 * Lightweight spam defense for the public forms: a honeypot field plus a
 * per-IP hourly cap, backed by the submission_log table. No CSRF token here
 * on purpose — see schema.sql / plan notes for why (static HTML pages, no
 * authenticated session to hijack, so bots are the real threat, not CSRF).
 */

require_once __DIR__ . '/../db.php';

/** @param array $data the decoded form payload */
function honeypot_tripped(array $data): bool {
    // Form field name deliberately generic/plausible so bots fill it; real
    // users never see it (hidden via CSS, not just visually off-screen).
    return !empty($data['website']);
}

function rate_limit_exceeded(string $ip): bool {
    $pdo = db();
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM submission_log WHERE ip_address = ? AND created_at >= datetime('now', '-1 hour')"
    );
    // SQLite-flavoured datetime() above; MySQL needs NOW() - INTERVAL 1 HOUR.
    if ((defined('DB_DRIVER') ? DB_DRIVER : 'mysql') !== 'sqlite') {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM submission_log WHERE ip_address = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)"
        );
    }
    $stmt->execute([$ip]);
    $count = (int) $stmt->fetchColumn();
    return $count >= RATE_LIMIT_MAX_PER_HOUR;
}

function record_submission(string $ip): void {
    $pdo = db();
    $pdo->prepare('INSERT INTO submission_log (ip_address) VALUES (?)')->execute([$ip]);
}
