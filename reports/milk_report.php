<?php

$pageTitle = "Milk Production Report";
$activePage = "reports";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

$query = mysqli_query($conn,"
SELECT
    mr.*,
    c.tag_number,
    c.name
FROM milk_records mr
INNER JOIN cows c
ON mr.cow_id=c.id
WHERE mr.production_date
BETWEEN '$from' AND '$to'
ORDER BY mr.production_date DESC,mr.session ASC
");

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$summary=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

IFNULL(SUM(quantity),0) total,

IFNULL(AVG(quantity),0) average,

SUM(CASE
WHEN session='Morning'
THEN quantity
ELSE 0 END) morning,

SUM(CASE
WHEN session='Evening'
THEN quantity
ELSE 0 END) evening

FROM milk_records

WHERE production_date
BETWEEN '$from' AND '$to'
"));

$totalMilk=$summary['total'];

$averageMilk=$summary['average'];

$morningMilk=$summary['morning'];

$eveningMilk=$summary['evening'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-droplet-half"></i>

Milk Production Report

</h2>

<p>

Analyze milk production performance within a selected period.

</p>

</div>

<!-- FILTER -->

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
href="milk_report.php"
class="btn btn-secondary">

<i class="bi bi-arrow-clockwise"></i>

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<!-- SUMMARY CARDS -->

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-green">

<i class="bi bi-droplet-fill"></i>

<h5>Total Milk</h5>

<h2><?= number_format($totalMilk,2) ?> L</h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-blue">

<i class="bi bi-sun-fill"></i>

<h5>Morning</h5>

<h2><?= number_format($morningMilk,2) ?> L</h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-orange">

<i class="bi bi-moon-stars-fill"></i>

<h5>Evening</h5>

<h2><?= number_format($eveningMilk,2) ?> L</h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-red">

<i class="bi bi-graph-up-arrow"></i>

<h5>Average</h5>

<h2><?= number_format($averageMilk,2) ?> L</h2>

</div>

</div>

</div>
<div class="dashboard-panel">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-table"></i>

Milk Production Records

</h5>

<div>

<button
class="btn btn-outline-success btn-sm"
onclick="window.print()">

<i class="bi bi-printer"></i>

Print Report

</button>

</div>

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

<th>Session</th>

<th>Quantity (L)</th>

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

<?= date("d M Y", strtotime($row['production_date'])); ?>

</td>

<td>

<strong><?= htmlspecialchars($row['tag_number']); ?></strong>

</td>

<td>

<?= htmlspecialchars($row['name']); ?>

</td>

<td>

<?php

if($row['session']=="Morning")
{

echo '<span class="badge bg-warning text-dark">
<i class="bi bi-sun-fill"></i>
Morning
</span>';

}
else
{

echo '<span class="badge bg-primary">
<i class="bi bi-moon-stars-fill"></i>
Evening
</span>';

}

?>

</td>

<td>

<strong>

<?= number_format($row['quantity'],2); ?> L

</strong>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="6" class="text-center text-muted py-4">

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No milk production records found for the selected period.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="5" class="text-end">

Total Milk Produced

</th>

<th>

<?= number_format($totalMilk,2); ?> L

</th>

</tr>

</tfoot>

</table>

</div>

</div>

</div>
<?php

/*
|--------------------------------------------------------------------------
| CHART DATA
|--------------------------------------------------------------------------
*/

$chartQuery = mysqli_query($conn,"
SELECT
    production_date,
    SUM(quantity) AS total
FROM milk_records
WHERE production_date BETWEEN '$from' AND '$to'
GROUP BY production_date
ORDER BY production_date ASC
");

$labels = [];
$data = [];

while($chart=mysqli_fetch_assoc($chartQuery))
{
    $labels[] = date("d M",strtotime($chart['production_date']));
    $data[] = (float)$chart['total'];
}

?>

<div class="dashboard-panel mt-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-graph-up-arrow"></i>

Daily Milk Production Trend

</h5>

</div>

<div class="dashboard-panel-body">

<canvas id="milkTrendChart" height="100"></canvas>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const milkChart=document.getElementById('milkTrendChart');

new Chart(milkChart,{

type:'line',

data:{

labels:<?= json_encode($labels); ?>,

datasets:[{

label:'Milk Produced (Litres)',

data:<?= json_encode($data); ?>,

borderColor:'#198754',

backgroundColor:'rgba(25,135,84,.12)',

fill:true,

borderWidth:3,

tension:.4,

pointRadius:5,

pointHoverRadius:7

}]

},

options:{

responsive:true,

plugins:{

legend:{

display:true

}

},

scales:{

y:{

beginAtZero:true,

title:{

display:true,

text:'Litres'

}

},

x:{

title:{

display:true,

text:'Production Date'

}

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

}

}

</style>

<?php include "../includes/footer.php"; ?>

</body>
</html>