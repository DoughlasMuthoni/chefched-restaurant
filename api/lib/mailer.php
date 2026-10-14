<?php
/**
 * Email notification via PHPMailer (vendored in api/vendor, no Composer
 * needed at runtime — just upload the vendor/ folder as-is). Falls back to
 * logging instead of sending when SMTP isn't configured (local dev).
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

function send_notification_email(string $subject, string $htmlBody): bool {
    if (empty(SMTP_HOST) || empty(SMTP_USER)) {
        error_log("[email:not-configured] $subject\n$htmlBody");
        return false;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->Timeout = 8;

        $mail->setFrom(NOTIFY_EMAIL_FROM, 'Njogu Nderitu Website');
        $mail->addAddress(NOTIFY_EMAIL_TO);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log('[email:error] ' . $mail->ErrorInfo);
        return false;
    }
}

function render_notification_html(string $title, array $fields): string {
    $rows = '';
    foreach ($fields as $label => $value) {
        $value = htmlspecialchars((string) $value, ENT_QUOTES);
        $label = htmlspecialchars((string) $label, ENT_QUOTES);
        $rows .= "<tr><td style=\"padding:6px 12px;color:#888;\">{$label}</td><td style=\"padding:6px 12px;\"><strong>{$value}</strong></td></tr>";
    }
    $title = htmlspecialchars($title, ENT_QUOTES);
    return "<h2 style=\"font-family:sans-serif;\">{$title}</h2><table style=\"font-family:sans-serif;border-collapse:collapse;\">{$rows}</table>";
}
