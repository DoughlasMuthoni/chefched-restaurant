<?php
/**
 * One-way WhatsApp notification via CallMeBot — pings Njogu Nderitu's own phone
 * when a new enquiry/message arrives. Best-effort: failures are logged and
 * never block the form submission from succeeding (the DB row + email are
 * the source of truth; WhatsApp is a convenience ping on top).
 *
 * Swap-in point for the official Meta WhatsApp Cloud API later: keep this
 * function's signature the same, change only what's inside it.
 */

require_once __DIR__ . '/../config.php';

function send_whatsapp_notification(string $text): bool {
    if (!defined('WHATSAPP_ENABLED') || !WHATSAPP_ENABLED) {
        error_log('[whatsapp:disabled] ' . $text);
        return false;
    }
    if (empty(WHATSAPP_PHONE) || empty(WHATSAPP_API_KEY)) {
        error_log('[whatsapp:not-configured] ' . $text);
        return false;
    }

    $url = 'https://api.callmebot.com/whatsapp.php?' . http_build_query([
        'phone'  => WHATSAPP_PHONE,
        'text'   => $text,
        'apikey' => WHATSAPP_API_KEY,
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
    ]);
    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno || $httpCode >= 400) {
        error_log("[whatsapp:error] errno=$errno http=$httpCode response=" . substr((string) $response, 0, 300));
        return false;
    }

    return true;
}
