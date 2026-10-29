<?php
require_once "../includes/system_settings.php";


$currentModule = basename(dirname($_SERVER['PHP_SELF']));
$currentPage   = basename($_SERVER['PHP_SELF']);

function activeMenu($module, $pages = [])
{
    global $currentModule, $currentPage;

    if ($currentModule != $module) {
        return "";
    }

    if (empty($pages)) {
        return "active";
    }

    return in_array($currentPage, $pages)
        ? "active"
        : "";
}
?>

<aside class="sidebar">

    <!-- ========================================= -->
    <!-- BRAND -->
    <!-- ========================================= -->

    <div class="sidebar-brand">

        <img
        src="<?php echo BASE_URL; ?>/assets/images/logo.png"
        class="sidebar-logo"
        alt="Logo">

        <h3>KDFMS</h3>

        <span>Kapkatet Dairy Farm</span>

    </div>

    <!-- ========================================= -->
    <!-- USER -->
    <!-- ========================================= -->

    <div class="sidebar-user">

        <div class="sidebar-user-avatar">

            <i class="bi bi-person-fill"></i>

        </div>

        <div class="sidebar-user-info">

            <h6>

                <?= htmlspecialchars($_SESSION['fullname']); ?>

            </h6>

            <small>

                <?= ucfirst($_SESSION['role']); ?>

            </small>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- MENU -->
    <!-- ========================================= -->

    <ul class="sidebar-nav">

        <li>

            <a class="<?= activeMenu('dashboard'); ?>"
            href="<?= BASE_URL; ?>/dashboard/index.php">

                <i class="bi bi-speedometer2"></i>

                <span>Dashboard</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('cows'); ?>"
            href="<?= BASE_URL; ?>/cows/manage.php">

                <i class="bi bi-tags-fill"></i>

                <span>Cow Management</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('milk'); ?>"
            href="<?= BASE_URL; ?>/milk/records.php">

                <i class="bi bi-droplet-half"></i>

                <span>Milk Production</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('health'); ?>"
            href="<?= BASE_URL; ?>/health/index.php">

                <i class="bi bi-heart-pulse-fill"></i>

                <span>Health Records</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('breeding'); ?>"
            href="<?= BASE_URL; ?>/breeding/index.php">

                <i class="bi bi-gender-female"></i>

                <span>Breeding</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('feed'); ?>"
             href="<?= BASE_URL; ?>/feed/index.php">

                <i class="bi bi-basket-fill"></i>

                <span>Feed Management</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('employees'); ?>"
             href="<?= BASE_URL; ?>/employees/index.php">

                <i class="bi bi-people-fill"></i>

                <span>Employees</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('sales'); ?>"
            href="<?= BASE_URL; ?>/sales/records.php">

                <i class="bi bi-cash-stack"></i>

                <span>Sales</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('reports'); ?>"
             href="<?= BASE_URL; ?>/reports/dashboard.php">

                <i class="bi bi-bar-chart-fill"></i>

                <span>Reports</span>

            </a>

        </li>

        <li>

            <a class="<?= activeMenu('settings'); ?>"
             href="<?= BASE_URL; ?>/settings/index.php">

                <i class="bi bi-gear-fill"></i>

                <span>Settings</span>

            </a>

        </li>

    </ul>

    <!-- ========================================= -->
    <!-- LOGOUT -->
    <!-- ========================================= -->

    <div class="sidebar-logout">

        <a href="<?= BASE_URL; ?>/auth/logout.php">

            <i class="bi bi-box-arrow-right"></i>

            <span>Logout</span>

        </a>

    </div>

</aside>