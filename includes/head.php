<?php
/* ================================================
   includes/head.php - HTML Head / Meta Tags
   Usage: include with $page_title set beforehand
   ================================================ */
$page_title = $page_title ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hostel Mate - Find and book student accommodation near Mbarara University of Science and Technology campus.">
    <title><?= htmlspecialchars($page_title) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <!-- Google Fonts preconnect for speed -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

