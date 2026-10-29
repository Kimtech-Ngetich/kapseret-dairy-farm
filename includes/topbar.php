<?php
require_once "../includes/system_settings.php";
if (!isset($pageTitle)) {
    $pageTitle = "Dashboard";
}

$userName = $_SESSION['fullname'] ?? 'Administrator';
$userRole = ucfirst($_SESSION['role'] ?? 'Admin');

?>

<header class="topbar">

    <!-- Left -->

    <div class="topbar-left">

        <button
            class="menu-toggle"
            id="menuToggle">

            <i class="bi bi-list"></i>

        </button>

        <div class="page-info">

            <h3>

                <?php echo htmlspecialchars($pageTitle); ?>

            </h3>

            <small>

                Welcome back,
                <strong><?php echo htmlspecialchars($userName); ?></strong>

            </small>

        </div>

    </div>

    <!-- Center -->

    <div class="topbar-center">

        <div class="top-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                placeholder="Search...">

        </div>

    </div>

    <!-- Right -->

    <div class="topbar-right">

        <!-- Date -->

        <div class="top-date">

            <i class="bi bi-calendar3"></i>

            <span>

                <?php echo date("l, d M Y"); ?>

            </span>

        </div>

        <!-- Clock -->

        <div class="top-clock">

            <i class="bi bi-clock"></i>

            <span id="liveClock"></span>

        </div>

        <!-- Notifications -->

        <button class="notification-btn">

            <i class="bi bi-bell-fill"></i>

            <span class="badge rounded-pill bg-danger">

                0

            </span>

        </button>

        <!-- User -->

        <div class="user-profile">

            <div class="user-avatar">

                <i class="bi bi-person-circle"></i>

            </div>

            <div class="user-details">

                <strong>

                    <?php echo htmlspecialchars($userName); ?>

                </strong>

                <small>

                    <?php echo htmlspecialchars($userRole); ?>

                </small>

            </div>

        </div>

    </div>

</header>

