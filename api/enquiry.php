<?php
/**
 * POST /api/enquiry.php — the "Book Njogu Nderitu" form on reservations.html.
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
    // Pretend success so bots don't learn anything; just don't save/notify.
    json_response(['status' => 'success', 'message' => 'Thank you — we will be in touch soon.']);
}

if (rate_limit_exceeded($ip)) {
    json_response(['status' => 'error', 'message' => 'Too many submissions from this network. Please try again in a bit, or reach out on WhatsApp/email directly.'], 429);
}

$name = trimmed($data, 'name', 120);
$email = trimmed($data, 'email', 190);
$phone = clean_phone(trimmed($data, 'phone', 40));
$eventType = trimmed($data, 'event_type', 60);
$eventDate = trimmed($data, 'event_date', 20);
$location = trimmed($data, 'location', 190);
$guests = trimmed($data, 'guests', 20);
$serviceRequired = trimmed($data, 'service_required', 60);
$message = trimmed($data, 'message', 3000);

$errors = [];
if ($name === '') $errors['name'] = 'Please enter your name.';
if (!is_valid_email($email)) $errors['email'] = 'Please enter a valid email address.';
if ($phone === '') $errors['phone'] = 'Please enter a phone / WhatsApp number.';
if ($eventType === '') $errors['event_type'] = 'Please choose an event type.';
if ($location === '') $errors['location'] = 'Please tell us the location.';
if ($guests === '') $errors['guests'] = 'Please enter the number of guests.';
if ($serviceRequired === '') $errors['service_required'] = 'Please choose a service.';

if (!empty($errors)) {
    json_response(['status' => 'validation_error', 'errors' => $errors], 422);
}

$pdo = db();
$stmt = $pdo->prepare(
    'INSERT INTO enquiries (name, email, phone, event_type, event_date, location, guests, service_required, message, ip_address)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$name, $email, $phone, $eventType, $eventDate ?: null, $location, $guests, $serviceRequired, $message, $ip]);
$enquiryId = (int) $pdo->lastInsertId();
record_submission($ip);

$summary = [
    'Name' => $name,
    'Phone / WhatsApp' => $phone,
    'Email' => $email,
    'Event Type' => $eventType,
    'Event Date' => $eventDate ?: '—',
    'Location' => $location,
    'Guests' => $guests,
    'Service Required' => $serviceRequired,
    'Message' => $message ?: '—',
];

$waText = "New booking enquiry on the website!\n"
    . "Name: {$name}\nPhone: {$phone}\nEvent: {$eventType} on " . ($eventDate ?: 'date not given') . "\n"
    . "Location: {$location}\nGuests: {$guests}\nService: {$serviceRequired}";
$whatsappOk = send_whatsapp_notification($waText);

$emailOk = send_notification_email(
    'New booking enquiry — ' . $name,
    render_notification_html('New Booking Enquiry', $summary)
);

$pdo->prepare('UPDATE enquiries SET whatsapp_sent = ?, email_sent = ? WHERE id = ?')
    ->execute([$whatsappOk ? 1 : 0, $emailOk ? 1 : 0, $enquiryId]);

json_response(['status' => 'success', 'message' => "Thank you, {$name}! Your enquiry has been received — Njogu Nderitu will be in touch shortly."]);
