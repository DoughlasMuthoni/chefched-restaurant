<?php
/**
 * Creates/updates the schema for whichever DB_DRIVER is active.
 * Local dev (sqlite): run `php api/migrate.php` any time.
 * Production (mysql): don't use this — import schema.sql via phpMyAdmin instead,
 * this file is only shipped for local development convenience.
 */

require_once __DIR__ . '/db.php';

$pdo = db();
$driver = defined('DB_DRIVER') ? DB_DRIVER : 'mysql';

if ($driver === 'sqlite') {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS enquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT NOT NULL,
            event_type TEXT,
            event_date TEXT,
            location TEXT,
            guests TEXT,
            service_required TEXT,
            message TEXT,
            status TEXT NOT NULL DEFAULT 'new' CHECK(status IN ('new','contacted','booked','closed')),
            whatsapp_sent INTEGER NOT NULL DEFAULT 0,
            email_sent INTEGER NOT NULL DEFAULT 0,
            ip_address TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            message TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'new' CHECK(status IN ('new','read','responded')),
            whatsapp_sent INTEGER NOT NULL DEFAULT 0,
            email_sent INTEGER NOT NULL DEFAULT 0,
            ip_address TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS submission_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ip_address TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");
    echo "SQLite schema ready at " . DB_SQLITE_PATH . "\n";
} else {
    echo "DB_DRIVER is 'mysql' — run schema.sql via phpMyAdmin instead of this script.\n";
}
