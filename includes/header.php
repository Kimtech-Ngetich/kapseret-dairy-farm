<?php

/**
 * ==========================================================
 * KAPKATET DAIRY FARM MANAGEMENT SYSTEM
 * HEADER
 * ==========================================================
 */

require_once __DIR__ . '/../config/app.php';
require_once "../includes/system_settings.php";

if (!isset($pageTitle) || empty($pageTitle)) {
    $pageTitle = APP_NAME;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="Kapkatet Dairy Farm Management System">

    <meta
        name="author"
        content="Carlos Kimutai">

    <meta
        name="theme-color"
        content="#198754">

    <title>

        <?php echo htmlspecialchars($pageTitle); ?>

    </title>

    <!-- =====================================================
         FAVICON
    ====================================================== -->

    <link
        rel="icon"
        href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- =====================================================
         CHART.JS
    ====================================================== -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- =====================================================
         CUSTOM CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/forms.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/tables.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/sidebar.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/topbar.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/footer.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/animations.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/responsive.css">

</head>

<body class="page-transition">

<div class="app-wrapper">