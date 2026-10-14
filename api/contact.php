<?php
/**
 * POST /api/contact.php — the contact form on contact-us.html.
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lib/validate.php';
require_once __DIR__ . '/lib/ratelimit.php';
require_once __DIR__ . '/lib/whatsapp.php';
require_once __DIR__ . '/lib/mailer.php';

require_post();
$data = read_json_or_post();
$ip = client_ip();

if (honeypot_tripped($data)) {
    json_response(['status' => 'success', 'message' => 'Message sent successfully!']);
}

if (rate_limit_exceeded($ip)) {
    json_response(['status' => 'error', 'message' => 'Too many submissions from this network. Please try again in a bit.'], 429);
}

$name = trimmed($data, 'name', 120);
$email = trimmed($data, 'email', 190);
$phone = clean_phone(trimmed($data, 'phone', 40));
$message = trimmed($data, 'message', 3000);

$errors = [];
if ($name === '') $errors['name'] = 'Please enter your name.';
if (!is_valid_email($email)) $errors['email'] = 'Please enter a valid email address.';
if ($message === '') $errors['message'] = 'Please enter a message.';

if (!empty($errors)) {
    json_response(['status' => 'validation_error', 'errors' => $errors], 422);
}

$pdo = db();
$stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, phone, message, ip_address) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$name, $email, $phone, $message, $ip]);
$messageId = (int) $pdo->lastInsertId();
record_submission($ip);

$waText = "New contact message on the website!\nName: {$name}\nPhone: " . ($phone ?: '—') . "\nEmail: {$email}\n\n{$message}";
$whatsappOk = send_whatsapp_notification($waText);

$emailOk = send_notification_email(
    'New contact message — ' . $name,
    render_notification_html('New Contact Message', [
        'Name' => $name,
        'Email' => $email,
        'Phone' => $phone ?: '—',
        'Message' => $message,
    ])
);

$pdo->prepare('UPDATE contact_messages SET whatsapp_sent = ?, email_sent = ? WHERE id = ?')
    ->execute([$whatsappOk ? 1 : 0, $emailOk ? 1 : 0, $messageId]);

json_response(['status' => 'success', 'message' => 'Message Sent successfully!']);
