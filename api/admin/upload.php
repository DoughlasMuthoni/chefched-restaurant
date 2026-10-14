<?php
/**
 * Shared image upload endpoint used by every CMS content type (services,
 * gallery, journal, homepage). Validates the file is actually an image
 * (not just trusting its extension) before saving it under /uploads/<folder>/.
 */
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
}

$allowedFolders = ['services', 'gallery', 'blog', 'homepage'];
$folder = trim((string) ($_POST['folder'] ?? ''));
if (!in_array($folder, $allowedFolders, true)) {
    json_response(['status' => 'error', 'message' => 'Invalid folder.'], 422);
}

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    json_response(['status' => 'error', 'message' => 'No valid image uploaded.'], 422);
}

$file = $_FILES['image'];
$maxBytes = 5 * 1024 * 1024;
if ($file['size'] > $maxBytes) {
    json_response(['status' => 'error', 'message' => 'Image must be 5MB or smaller.'], 422);
}

// Trust getimagesize()'s real decode, not the client-supplied extension/mime.
$imageInfo = @getimagesize($file['tmp_name']);
if ($imageInfo === false) {
    json_response(['status' => 'error', 'message' => 'File is not a valid image.'], 422);
}

$allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$mime = $imageInfo['mime'];
if (!isset($allowedMime[$mime])) {
    json_response(['status' => 'error', 'message' => 'Only JPG, PNG or WEBP images are allowed.'], 422);
}
$ext = $allowedMime[$mime];

$targetDir = __DIR__ . '/../../uploads/' . $folder;
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0775, true);
}

$filename = bin2hex(random_bytes(8)) . '.' . $ext;
$targetPath = $targetDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    json_response(['status' => 'error', 'message' => 'Failed to save uploaded file.'], 500);
}

json_response(['status' => 'success', 'path' => 'uploads/' . $folder . '/' . $filename]);
