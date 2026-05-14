<?php
/* ================================================
   logout.php - Destroy Session & Redirect
   ================================================ */
require_once 'config.php';

session_unset();
session_destroy();

// Restart session for flash message
session_start();
setFlash('info', 'You have been logged out successfully.');

header('Location: ' . APP_URL . '/login.php');
exit;
