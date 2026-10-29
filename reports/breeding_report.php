<?php

$pageTitle = "Breeding Report";
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
| BREEDING RECORDS
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT

br.*,

c.tag_number,

c.name

FROM breeding_records br

INNER JOIN cows c

ON br.cow_id=c.id

WHERE br.breeding_date
BETWEEN '$from' AND '$to'

ORDER BY br.breeding_date DESC
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
WHEN pregnancy_status='Confirmed'
THEN 1 ELSE 0 END) confirmed,

SUM(CASE
WHEN pregnancy_status='Pending'
THEN 1 ELSE 0 END) pending,

SUM(CASE
WHEN calving_status='Calved'
THEN 1 ELSE 0 END) calved

FROM breeding_records

WHERE breeding_date
BETWEEN '$from' AND '$to'
"));

$totalRecords = $summary['total_records'];
$confirmed = $summary['confirmed'];
$pending = $summary['pending'];
$calved = $summary['calved'];

/*
|--------------------------------------------------------------------------
| PREGNANCY STATUS CHART
|--------------------------------------------------------------------------
*/

$statusQuery = mysqli_query($conn,"
SELECT

pregnancy_status,

COUNT(*) total

FROM breeding_records

WHERE breeding_date
BETWEEN '$from' AND '$to'

GROUP BY pregnancy_status
");

$statusLabels = [];
$statusData = [];

while($row = mysqli_fetch_assoc($statusQuery))
{
    $statusLabels[] = $row['pregnancy_status'];
    $statusData[] = (int)$row['total'];
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-fill"></i>

Breeding Report

</h2>

<p>

Monitor breeding activities, pregnancy status and calving performance.

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
href="breeding_report.php"
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

<i class="bi bi-journal-check"></i>

<h5>Total Records</h5>

<h2><?= number_format($totalRecords) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-primary">

<i class="bi bi-patch-check-fill"></i>

<h5>Confirmed Pregnancy</h5>

<h2><?= number_format($confirmed) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-warning">

<i class="bi bi-hourglass-split"></i>

<h5>Pending</h5>

<h2><?= number_format($pending) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-success">

<i class="bi bi-emoji-smile-fill"></i>

<h5>Successful Calving</h5>

<h2><?= number_format($calved) ?></h2>

</div>

</div>

</div>

<!-- REPORT ACTIONS -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-file-earmark-medical-fill"></i>

Breeding Records

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

Breeding Records Report

</h5>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Breeding Date</th>

<th>Tag Number</th>

<th>Cow Name</th>

<th>Breeding Method</th>

<th>Pregnancy Status</th>

<th>Expected Calving</th>

<th>Calving Status</th>

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

<?= date("d M Y", strtotime($row['breeding_date'])); ?>

</td>

<td>

<strong><?= htmlspecialchars($row['tag_number']); ?></strong>

</td>

<td>

<?= htmlspecialchars($row['name']); ?>

</td>

<td>

<?= htmlspecialchars($row['breeding_method']); ?>

</td>

<td>

<?php

switch($row['pregnancy_status'])
{

case "Confirmed":

echo '<span class="badge bg-success">
<i class="bi bi-check-circle-fill"></i>
 Confirmed
</span>';

break;

case "Pending":

echo '<span class="badge bg-warning text-dark">
<i class="bi bi-hourglass-split"></i>
 Pending
</span>';

break;

case "Not Pregnant":

echo '<span class="badge bg-danger">
<i class="bi bi-x-circle-fill"></i>
 Not Pregnant
</span>';

break;

default:

echo '<span class="badge bg-secondary">' .
htmlspecialchars($row['pregnancy_status']) .
'</span>';

}

?>

</td>

<td>

<?= !empty($row['expected_calving_date']) ? date("d M Y", strtotime($row['expected_calving_date'])) : "-" ?>

</td>

<td>

<?php

switch($row['calving_status'])
{

case "Calved":

echo '<span class="badge bg-primary">
<i class="bi bi-emoji-smile-fill"></i>
 Calved
</span>';

break;

case "Pending":

echo '<span class="badge bg-warning text-dark">
<i class="bi bi-clock-history"></i>
 Pending
</span>';

break;

default:

echo '<span class="badge bg-secondary">' .
htmlspecialchars($row['calving_status']) .
'</span>';

}

?>

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

<td colspan="9" class="text-center text-muted py-4">

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No breeding records found for the selected period.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="8" class="text-end">

Total Breeding Records

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

            Pregnancy Status Distribution

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <canvas id="pregnancyChart" height="100"></canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const pregnancyChart=document.getElementById("pregnancyChart");

new Chart(pregnancyChart,{

type:'doughnut',

data:{

labels:<?= json_encode($statusLabels); ?>,

datasets:[{

data:<?= json_encode($statusData); ?>,

backgroundColor:[

'#198754',

'#ffc107',

'#dc3545',

'#0d6efd',

'#20c997',

'#6f42c1',

'#fd7e14'

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

text:'Pregnancy Status Analysis'

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