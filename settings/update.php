<?php

$pageTitle = "Update Settings";
$activePage = "settings";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$success = "";
$error = "";

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

/*
|--------------------------------------------------------------------------
| UPDATE SETTINGS
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{
    $farm_name = mysqli_real_escape_string($conn,$_POST['farm_name']);
    $owner_name = mysqli_real_escape_string($conn,$_POST['owner_name']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $county = mysqli_real_escape_string($conn,$_POST['county']);
    $currency = mysqli_real_escape_string($conn,$_POST['currency']);
    $timezone = mysqli_real_escape_string($conn,$_POST['timezone']);
    $date_format = mysqli_real_escape_string($conn,$_POST['date_format']);
    $system_name = mysqli_real_escape_string($conn,$_POST['system_name']);
    $system_version = mysqli_real_escape_string($conn,$_POST['system_version']);
    $footer_text = mysqli_real_escape_string($conn,$_POST['footer_text']);
    $maintenance_mode = mysqli_real_escape_string($conn,$_POST['maintenance_mode']);

    $update = mysqli_query($conn,"
    UPDATE settings
    SET

    farm_name='$farm_name',
    owner_name='$owner_name',
    phone='$phone',
    email='$email',
    address='$address',
    county='$county',
    currency='$currency',
    timezone='$timezone',
    date_format='$date_format',
    system_name='$system_name',
    system_version='$system_version',
    footer_text='$footer_text',
    maintenance_mode='$maintenance_mode'

    WHERE id='{$settings['id']}'
    ");

    if($update)
    {
        $success = "System settings updated successfully.";

        $query = mysqli_query($conn,"
        SELECT *
        FROM settings
        LIMIT 1
        ");

        $settings = mysqli_fetch_assoc($query);
    }
    else
    {
        $error = "Unable to update system settings.";
    }
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-sliders"></i>

Update System Settings

</h2>

<p>

Manage farm information and system configuration.

</p>

</div>

<?php if(!empty($success)): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

<?= htmlspecialchars($success); ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(!empty($error)): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

<?= htmlspecialchars($error); ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<form method="POST">

<!-- ================================================= -->
<!-- FARM INFORMATION -->
<!-- ================================================= -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-building"></i>

Farm Information

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Farm Name</label>

<input
type="text"
name="farm_name"
class="form-control"
value="<?= htmlspecialchars($settings['farm_name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Owner Name</label>

<input
type="text"
name="owner_name"
class="form-control"
value="<?= htmlspecialchars($settings['owner_name']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Phone Number</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($settings['phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Email Address</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($settings['email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">County</label>

<input
type="text"
name="county"
class="form-control"
value="<?= htmlspecialchars($settings['county']); ?>">

</div>

<div class="col-12 mb-3">

<label class="form-label">Farm Address</label>

<textarea
name="address"
rows="4"
class="form-control"><?= htmlspecialchars($settings['address']); ?></textarea>

</div>

</div>

</div>

</div>

<!-- ================================================= -->
<!-- REGIONAL SETTINGS -->
<!-- ================================================= -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-globe-africa"></i>

Regional Settings

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">

Currency

</label>

<select name="currency" class="form-select">

<option value="KES" <?= $settings['currency']=="KES" ? "selected":""; ?>>KES</option>

<option value="USD" <?= $settings['currency']=="USD" ? "selected":""; ?>>USD</option>

<option value="EUR" <?= $settings['currency']=="EUR" ? "selected":""; ?>>EUR</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Timezone

</label>

<select name="timezone" class="form-select">

<option value="Africa/Nairobi" <?= $settings['timezone']=="Africa/Nairobi" ? "selected":""; ?>>Africa/Nairobi</option>

<option value="UTC" <?= $settings['timezone']=="UTC" ? "selected":""; ?>>UTC</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Date Format

</label>

<select name="date_format" class="form-select">

<option value="d M Y" <?= $settings['date_format']=="d M Y" ? "selected":""; ?>>13 Jul 2026</option>

<option value="Y-m-d" <?= $settings['date_format']=="Y-m-d" ? "selected":""; ?>>2026-07-13</option>

<option value="d/m/Y" <?= $settings['date_format']=="d/m/Y" ? "selected":""; ?>>13/07/2026</option>

</select>

</div>

</div>

</div>

</div>

<!-- ================================================= -->
<!-- SYSTEM SETTINGS -->
<!-- ================================================= -->

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-cpu-fill"></i>

System Settings

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

System Name

</label>

<input
type="text"
name="system_name"
class="form-control"
value="<?= htmlspecialchars($settings['system_name']); ?>">

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Version

</label>

<input
type="text"
name="system_version"
class="form-control"
value="<?= htmlspecialchars($settings['system_version']); ?>">

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Maintenance Mode

</label>

<select
name="maintenance_mode"
class="form-select">

<option value="No" <?= $settings['maintenance_mode']=="No" ? "selected":""; ?>>

Disabled

</option>

<option value="Yes" <?= $settings['maintenance_mode']=="Yes" ? "selected":""; ?>>

Enabled

</option>

</select>

</div>

<div class="col-12 mb-4">

<label class="form-label">

Footer Text

</label>

<textarea
name="footer_text"
rows="3"
class="form-control"><?= htmlspecialchars($settings['footer_text']); ?></textarea>

</div>

<div class="text-end">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-save"></i>

Save Settings

</button>

</div>

</div>

</div>

</div>

</form>
</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    const farmName = document.querySelector("[name='farm_name']");
    const ownerName = document.querySelector("[name='owner_name']");
    const phone = document.querySelector("[name='phone']");
    const email = document.querySelector("[name='email']");
    const county = document.querySelector("[name='county']");
    const systemName = document.querySelector("[name='system_name']");
    const version = document.querySelector("[name='system_version']");

    form.addEventListener("submit", function(e){

        if(farmName.value.trim() === "")
        {
            alert("Farm name is required.");
            farmName.focus();
            e.preventDefault();
            return;
        }

        if(ownerName.value.trim() === "")
        {
            alert("Owner name is required.");
            ownerName.focus();
            e.preventDefault();
            return;
        }

        if(email.value !== "")
        {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!emailPattern.test(email.value))
            {
                alert("Please enter a valid email address.");
                email.focus();
                e.preventDefault();
                return;
            }
        }

        if(phone.value !== "")
        {
            const phonePattern = /^[0-9+\-\s]{10,15}$/;

            if(!phonePattern.test(phone.value))
            {
                alert("Please enter a valid phone number.");
                phone.focus();
                e.preventDefault();
                return;
            }
        }

        if(county.value.trim() === "")
        {
            alert("County is required.");
            county.focus();
            e.preventDefault();
            return;
        }

        if(systemName.value.trim() === "")
        {
            alert("System name is required.");
            systemName.focus();
            e.preventDefault();
            return;
        }

        if(version.value.trim() === "")
        {
            alert("System version is required.");
            version.focus();
            e.preventDefault();
            return;
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>