<?php

$pageTitle = "Health Report";
$activePage = "reports";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| DATE FILTER
|--------------------------------------------------------------------------
*/

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

/*
|--------------------------------------------------------------------------
| HEALTH RECORDS
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT

hr.*,

c.tag_number,

c.name

FROM health_records hr

INNER JOIN cows c

ON hr.cow_id=c.id

WHERE hr.record_date
BETWEEN '$from' AND '$to'

ORDER BY hr.record_date DESC

");

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$summary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

COUNT(*) total_records,

SUM(CASE
WHEN health_status='Healthy'
THEN 1 ELSE 0 END) healthy,

SUM(CASE
WHEN health_status='Sick'
THEN 1 ELSE 0 END) sick,

SUM(CASE
WHEN health_status='Under Treatment'
THEN 1 ELSE 0 END) treatment

FROM health_records

WHERE record_date
BETWEEN '$from' AND '$to'

"));

$totalRecords = $summary['total_records'];

$healthy = $summary['healthy'];

$sick = $summary['sick'];

$treatment = $summary['treatment'];

/*
|--------------------------------------------------------------------------
| DISEASE STATISTICS
|--------------------------------------------------------------------------
*/

$diseaseQuery = mysqli_query($conn,"
SELECT

disease,

COUNT(*) total

FROM health_records

WHERE record_date
BETWEEN '$from' AND '$to'

GROUP BY disease

ORDER BY total DESC

");

$diseaseLabels = [];
$diseaseData = [];

while($row = mysqli_fetch_assoc($diseaseQuery))
{

$diseaseLabels[] = $row['disease'];

$diseaseData[] = (int)$row['total'];

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-pulse-fill"></i>

Health Report

</h2>

<p>

Monitor animal health records, treatments and disease trends.

</p>

</div>

<!-- FILTER PANEL -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-funnel-fill"></i>

Filter Report

</h5>

</div>

<div class="dashboard-panel-body">

<form method="GET">

<div class="row">

<div class="col-md-4">

<label class="form-label">

From

</label>

<input
type="date"
name="from"
class="form-control"
value="<?= $from ?>">

</div>

<div class="col-md-4">

<label class="form-label">

To

</label>

<input
type="date"
name="to"
class="form-control"
value="<?= $to ?>">

</div>

<div class="col-md-4 d-flex align-items-end">

<button
class="btn btn-success me-2">

<i class="bi bi-search"></i>

Generate Report

</button>

<a
href="health_report.php"
class="btn btn-secondary">

<i class="bi bi-arrow-clockwise"></i>

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<!-- KPI CARDS -->

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-green">

<i class="bi bi-clipboard2-pulse-fill"></i>

<h5>Total Records</h5>

<h2><?= number_format($totalRecords) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-success">

<i class="bi bi-heart-fill"></i>

<h5>Healthy</h5>

<h2><?= number_format($healthy) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-danger">

<i class="bi bi-heartbreak-fill"></i>

<h5>Sick</h5>

<h2><?= number_format($sick) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-warning">

<i class="bi bi-capsule-pill"></i>

<h5>Under Treatment</h5>

<h2><?= number_format($treatment) ?></h2>

</div>

</div>

</div>

<!-- REPORT ACTIONS -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-file-medical-fill"></i>

Health Records

</h5>

<button
class="btn btn-outline-success btn-sm"
onclick="window.print()">

<i class="bi bi-printer"></i>

Print Report

</button>

</div>

</div>
<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-table"></i>

Health Records Report

</h5>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Date</th>

<th>Tag Number</th>

<th>Cow Name</th>

<th>Health Status</th>

<th>Disease / Diagnosis</th>

<th>Treatment</th>

<th>Remarks</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

if(mysqli_num_rows($query) > 0):

while($row = mysqli_fetch_assoc($query)):

?>

<tr>

<td><?= $no++; ?></td>

<td>

<?= date("d M Y", strtotime($row['record_date'])); ?>

</td>

<td>

<strong><?= htmlspecialchars($row['tag_number']); ?></strong>

</td>

<td>

<?= htmlspecialchars($row['name']); ?>

</td>

<td>

<?php

switch($row['health_status'])
{

case "Healthy":

echo '<span class="badge bg-success">
<i class="bi bi-heart-fill"></i>
 Healthy
</span>';

break;

case "Sick":

echo '<span class="badge bg-danger">
<i class="bi bi-heartbreak-fill"></i>
 Sick
</span>';

break;

case "Under Treatment":

echo '<span class="badge bg-warning text-dark">
<i class="bi bi-capsule-pill"></i>
 Under Treatment
</span>';

break;

default:

echo '<span class="badge bg-secondary">' .
htmlspecialchars($row['health_status']) .
'</span>';

}

?>

</td>

<td>

<?= htmlspecialchars($row['disease']); ?>

</td>

<td>

<?= htmlspecialchars($row['treatment']); ?>

</td>

<td>

<?= !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : '-' ?>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="8" class="text-center text-muted py-4">

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No health records found for the selected period.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="7" class="text-end">

Total Health Records

</th>

<th>

<?= number_format($totalRecords); ?>

</th>

</tr>

</tfoot>

</table>

</div>

</div>

</div>
<div class="dashboard-panel mt-4">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-pie-chart-fill"></i>

            Disease Distribution Analysis

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <canvas id="diseaseChart" height="100"></canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const diseaseChart=document.getElementById("diseaseChart");

new Chart(diseaseChart,{

type:'doughnut',

data:{

labels:<?= json_encode($diseaseLabels); ?>,

datasets:[{

data:<?= json_encode($diseaseData); ?>,

backgroundColor:[

'#198754',

'#dc3545',

'#0d6efd',

'#ffc107',

'#20c997',

'#6f42c1',

'#fd7e14',

'#6610f2',

'#0dcaf0',

'#6c757d'

],

borderColor:'#ffffff',

borderWidth:2,

hoverOffset:8

}]

},

options:{

responsive:true,

plugins:{

legend:{

position:'bottom'

},

title:{

display:true,

text:'Disease Occurrence by Diagnosis'

}

}

}

});

</script>

<style>

@media print{

.sidebar,
.topbar,
.btn,
.page-header p{

display:none !important;

}

.main-content{

margin:0 !important;

padding:0 !important;

}

.dashboard-panel{

box-shadow:none;

border:1px solid #ddd;

margin-bottom:20px;

}

table{

font-size:12px;

}

}

</style>

<?php include "../includes/footer.php"; ?>

</body>
</html>