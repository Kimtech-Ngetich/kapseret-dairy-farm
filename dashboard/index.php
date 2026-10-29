<?php
/**
 * KDFMS Dashboard (Starter Professional Template)
 * Replace your existing dashboard/index.php with this file.
 * NOTE: Keep your existing SQL queries above the includes if desired.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Dashboard";
$activePage = "dashboard";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/* KPI Queries */
$cows = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM cows WHERE status='Active'"));
$total_cows = (int)($cows['total'] ?? 0);

$milk = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(quantity) total FROM milk_records WHERE production_date=CURDATE()"));
$milk_today = (float)($milk['total'] ?? 0);

$sales = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total_amount) revenue FROM sales WHERE sale_date=CURDATE()"));
$revenue_today = (float)($sales['revenue'] ?? 0);

$pregnant_cows = 0;

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<main class="main-content dashboard-wrapper container-fluid">

<div class="mb-4">
    <h2 class="dashboard-title">Dashboard</h2>
    <p class="dashboard-subtitle">
        Welcome back,
        <strong><?= htmlspecialchars($_SESSION['fullname'] ?? 'Administrator'); ?></strong>.
        Here's today's farm overview.
    </p>
</div>

<div class="row g-4">

<div class="col-lg-3 col-md-6">
<div class="kpi-card kpi-green">
<h6>Total Active Cows</h6>
<h2><?= $total_cows ?></h2>
<i class="bi bi-tags-fill"></i>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="kpi-card kpi-blue">
<h6>Milk Today</h6>
<h2><?= number_format($milk_today,1) ?> L</h2>
<i class="bi bi-droplet-fill"></i>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="kpi-card kpi-orange">
<h6>Revenue Today</h6>
<h2>KES <?= number_format($revenue_today,2) ?></h2>
<i class="bi bi-cash-stack"></i>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="kpi-card kpi-red">
<h6>Pregnant Cows</h6>
<h2><?= $pregnant_cows ?></h2>
<i class="bi bi-heart-pulse-fill"></i>
</div>
</div>

</div>

<div class="row mt-4">

<div class="col-lg-8">

<div class="dashboard-panel">
<div class="dashboard-panel-header">
<h5><i class="bi bi-graph-up-arrow"></i> Weekly Milk Production</h5>
</div>
<div class="dashboard-panel-body">
<div class="chart-box">
<canvas id="milkChart"></canvas>
</div>
</div>
</div>

<div class="dashboard-panel mt-4">
<div class="dashboard-panel-header">
<h5><i class="bi bi-clock-history"></i> Recent Activities</h5>
</div>
<div class="dashboard-panel-body">

<?php
$q=mysqli_query($conn,"SELECT activity,created_at FROM activities ORDER BY id DESC LIMIT 5");
if(mysqli_num_rows($q)):
while($r=mysqli_fetch_assoc($q)):
?>
<div class="notification-item">
<div class="notification-icon"><i class="bi bi-check2-circle"></i></div>
<div class="notification-text">
<h6><?= htmlspecialchars($r['activity']) ?></h6>
<small><?= htmlspecialchars($r['created_at']) ?></small>
</div>
</div>
<?php endwhile; else: ?>
<p>No recent activities.</p>
<?php endif; ?>

</div>
</div>

</div>

<div class="col-lg-4">

<div class="dashboard-panel">
<div class="dashboard-panel-header"><h5>Quick Actions</h5></div>
<div class="dashboard-panel-body">
<div class="d-grid gap-2">
<a href="../cows/add.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Cow</a>
<a href="../milk/add.php" class="btn btn-primary"><i class="bi bi-droplet"></i> Record Milk</a>
<a href="../health/add.php" class="btn btn-danger"><i class="bi bi-heart-pulse"></i> Health Record</a>
<a href="../sales/add.php" class="btn btn-warning"><i class="bi bi-cash"></i> Record Sale</a>
</div>
</div>
</div>

<div class="dashboard-panel mt-4">
<div class="dashboard-panel-header"><h5>Farm Status</h5></div>
<div class="dashboard-panel-body status-widget">
<i class="bi bi-building-check"></i>
<h3>Operational</h3>
<p>Kapketet Dairy Farm Management System</p>
</div>
</div>

</div>

</div>

<script>
const ctx=document.getElementById('milkChart');
if(ctx){
new Chart(ctx,{
type:'line',
data:{
labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
datasets:[{
label:'Milk (Litres)',
data:[120,150,165,180,170,210,225],
fill:true,
tension:.4
}]
},
options:{responsive:true,maintainAspectRatio:false}
});
}
</script>

</main>

<?php include "../includes/footer.php"; ?>
