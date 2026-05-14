<?php
/* ================================================
   config.php - Database Configuration
   MUST Hostel Finder System
   ================================================ */

// --- Database Settings (change for your XAMPP setup) ---
define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'must_hostelfinder');
define('DB_CHARSET',  'utf8mb4');

// --- Application Settings ---
define('APP_NAME',    'MUST Hostel Finder');
define('APP_URL',     'http://localhost/hostels');
define('APP_VERSION', '1.0.0');

// --- Create Database Connection ---
function getDB() {
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            die('<div style="font-family:sans-serif;padding:40px;text-align:center;color:#B91C1C;background:#FEE2E2;margin:40px;border-radius:12px;"><strong>Database Connection Failed:</strong> ' . htmlspecialchars($conn->connect_error) . '<br><small>Make sure XAMPP MySQL is running and the database exists. See README.md for setup.</small></div>');
        }

        $conn->set_charset(DB_CHARSET);
    }

    return $conn;
}

// --- Session Start ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Helper: Check if user is logged in ---
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// --- Helper: Require login (redirect if not) ---
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// --- Helper: Check if admin ---
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// --- Helper: Require admin ---
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . APP_URL . '/dashboard.php');
        exit;
    }
}

// --- Helper: Sanitize input ---
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// --- Helper: Flash messages ---
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// --- Helper: Render alert HTML ---
function renderFlash() {
    $flash = getFlash();
    if ($flash) {
        $icons = ['success' => '✅', 'error' => '❌', 'info' => 'ℹ️', 'warning' => '⚠️'];
        $icon  = $icons[$flash['type']] ?? 'ℹ️';
        echo '<div class="alert alert-' . $flash['type'] . '">' . $icon . ' ' . sanitize($flash['message']) . '</div>';
    }
}

// --- Helper: Format currency ---
function formatPrice($amount) {
    return 'UGX ' . number_format((float)$amount, 0);
}

// --- Helper: Time ago ---
function timeAgo($datetime) {
    $now  = new DateTime();
    $ago  = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->d > 7)  return $ago->format('M j, Y');
    if ($diff->d > 0)  return $diff->d . 'd ago';
    if ($diff->h > 0)  return $diff->h . 'h ago';
    if ($diff->i > 0)  return $diff->i . 'm ago';
    return 'Just now';
}
