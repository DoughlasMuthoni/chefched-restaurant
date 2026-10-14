<?php
require_once __DIR__ . '/../lib/validate.php';
require_once __DIR__ . '/../lib/auth.php';

start_admin_session();
$_SESSION = [];
session_destroy();

json_response(['status' => 'success']);
