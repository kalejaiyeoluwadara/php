<?php
/**
 * Home - Redirect to dashboard if logged in, else login
 */

require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
