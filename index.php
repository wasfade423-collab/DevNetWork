<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/control.php';
$page = is_string($_GET['action'] ?? null) ? $_GET['action'] : 'home';

new Controller($page);
