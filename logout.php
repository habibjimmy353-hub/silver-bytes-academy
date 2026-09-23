<?php
require_once __DIR__ . '/includes/functions.php';

// Remove every piece of session data, then destroy the session
// completely and delete the session cookie from the browser.
// This is the correct, secure way to log a user out.
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

// Start a fresh session only so we can show a flash message.
session_start();
set_flash('success', 'You have been logged out safely.');
redirect('login.php');
