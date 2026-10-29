<?php

$pageTitle = "System Settings";
$activePage = "settings";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| LOAD SETTINGS
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *
FROM settings
LIMIT 1
");

if(mysqli_num_rows($query)==0)
{
    die("Settings record not found.");
}

$settings = mysqli_fetch_assoc($query);

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-gear-fill"></i>

System Settings

</h2>

<p>

Configure and manage the Kapseret Dairy Farm Management System.

</p>

</div>

<div class="row g-4">

<!-- Farm Information -->

<div class="col-lg-6">

<div class="dashboard-panel h-100">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-building"></i>

Farm Information

</h5>

</div>

<div class="dashboard-panel-body">

<p><strong>Farm Name:</strong><br><?= htmlspecialchars($settings['farm_name']); ?></p>

<p><strong>Owner:</strong><br><?= htmlspecialchars($settings['owner_name']); ?></p>

<p><strong>Phone:</strong><br><?= htmlspecialchars($settings['phone']); ?></p>

<p><strong>Email:</strong><br><?= htmlspecialchars($settings['email']); ?></p>

<p><strong>County:</strong><br><?= htmlspecialchars($settings['county']); ?></p>

</div>

</div>

</div>

<!-- Regional Settings -->

<div class="col-lg-6">

<div class="dashboard-panel h-100">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-globe-africa"></i>

Regional Settings

</h5>

</div>

<div class="dashboard-panel-body">

<p><strong>Currency:</strong> <?= htmlspecialchars($settings['currency']); ?></p>

<p><strong>Timezone:</strong> <?= htmlspecialchars($settings['timezone']); ?></p>

<p><strong>Date Format:</strong> <?= htmlspecialchars($settings['date_format']); ?></p>

</div>

</div>

</div>

<!-- System Information -->

<div class="col-lg-6">

<div class="dashboard-panel h-100">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-cpu-fill"></i>

System Information

</h5>

</div>

<div class="dashboard-panel-body">

<p><strong>System Name:</strong><br><?= htmlspecialchars($settings['system_name']); ?></p>

<p><strong>Version:</strong> <?= htmlspecialchars($settings['system_version']); ?></p>

<p><strong>Maintenance Mode:</strong>

<?php if($settings['maintenance_mode']=="Yes"): ?>

<span class="badge bg-danger">Enabled</span>

<?php else: ?>

<span class="badge bg-success">Disabled</span>

<?php endif; ?>

</p>

</div>

</div>

</div>

<!-- Quick Actions -->

<div class="col-lg-6">

<div class="dashboard-panel h-100">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-lightning-fill"></i>

Quick Actions

</h5>

</div>

<div class="dashboard-panel-body d-grid gap-2">

<a href="update.php" class="btn btn-success">

<i class="bi bi-pencil-square"></i>

Edit Settings

</a>

<a href="profile.php" class="btn btn-primary">

<i class="bi bi-person-circle"></i>

My Profile

</a>

<a href="change_password.php" class="btn btn-warning">

<i class="bi bi-key-fill"></i>

Change Password

</a>

<a href="users.php" class="btn btn-info">

<i class="bi bi-people-fill"></i>

User Management

</a>

<a href="backup.php" class="btn btn-secondary">

<i class="bi bi-cloud-arrow-down-fill"></i>

Backup Database

</a>

<a href="activity_logs.php" class="btn btn-dark">

<i class="bi bi-clock-history"></i>

Activity Logs

</a>

</div>

</div>

</div>

</div>
<!-- System Status -->

<div class="row mt-4">

    <div class="col-12">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <h5>

                    <i class="bi bi-info-circle-fill"></i>

                    System Status

                </h5>

            </div>

            <div class="dashboard-panel-body">

                <div class="row">

                    <div class="col-md-3 text-center">

                        <i class="bi bi-check-circle-fill text-success fs-1"></i>

                        <h6 class="mt-2">

                            Database

                        </h6>

                        <span class="badge bg-success">

                            Connected

                        </span>

                    </div>

                    <div class="col-md-3 text-center">

                        <i class="bi bi-hdd-network-fill text-primary fs-1"></i>

                        <h6 class="mt-2">

                            Server

                        </h6>

                        <span class="badge bg-primary">

                            Running

                        </span>

                    </div>

                    <div class="col-md-3 text-center">

                        <i class="bi bi-shield-lock-fill text-success fs-1"></i>

                        <h6 class="mt-2">

                            Security

                        </h6>

                        <span class="badge bg-success">

                            Protected

                        </span>

                    </div>

                    <div class="col-md-3 text-center">

                        <i class="bi bi-code-square text-warning fs-1"></i>

                        <h6 class="mt-2">

                            Version

                        </h6>

                        <span class="badge bg-warning text-dark">

                            <?= htmlspecialchars($settings['system_version']); ?>

                        </span>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <strong>Footer Text</strong>

                        <p class="text-muted mb-0">

                            <?= htmlspecialchars($settings['footer_text']); ?>

                        </p>

                    </div>

                    <div class="col-md-6 text-end">

                        <strong>Last Updated</strong>

                        <p class="text-muted mb-0">

                            <?= date("d M Y H:i", strtotime($settings['updated_at'])); ?>

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>