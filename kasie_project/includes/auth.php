<?php
/**
 * Authentication helper functions
 * Handles session management and role-based access control
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROLE_HIERARCHY', ['user' => 1, 'editor' => 2, 'admin' => 3]);

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user's role
 */
function getCurrentRole(): ?string {
    return $_SESSION['role'] ?? null;
}

/**
 * Check if user has at least the required role level
 */
function hasRole(string $minRole): bool {
    if (!isLoggedIn()) return false;
    $userRole = getCurrentRole();
    if (!$userRole) return false;
    return (ROLE_HIERARCHY[$userRole] ?? 0) >= (ROLE_HIERARCHY[$minRole] ?? 0);
}

/**
 * Require login - redirect to login if not authenticated
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Require specific role - redirect to dashboard if insufficient permissions
 */
function requireRole(string $minRole): void {
    requireLogin();
    if (!hasRole($minRole)) {
        header('Location: dashboard.php?error=insufficient_permissions');
        exit;
    }
}
