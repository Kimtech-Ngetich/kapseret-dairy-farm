<?php

$pageTitle = "Cow Report";
$activePage = "reports";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| COW RECORDS
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *
FROM cows
ORDER BY tag_number ASC
");

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$summary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

COUNT(*) total_cows,

SUM(CASE WHEN status='Active' THEN 1 ELSE 0 END) active_cows,

SUM(CASE WHEN status='Sold' THEN 1 ELSE 0 END) sold_cows,

SUM(CASE WHEN status='Dead' THEN 1 ELSE 0 END) dead_cows

FROM cows
"));

$totalCows = $summary['total_cows'];
$activeCows = $summary['active_cows'];
$soldCows = $summary['sold_cows'];
$deadCows = $summary['dead_cows'];

/*
|--------------------------------------------------------------------------
| BREED DISTRIBUTION
|--------------------------------------------------------------------------
*/

$breedQuery = mysqli_query($conn,"
SELECT
breed,
COUNT(*) total
FROM cows
GROUP BY breed
ORDER BY total DESC
");

$breedLabels = [];
$breedData = [];

while($breed = mysqli_fetch_assoc($breedQuery))
{
    $breedLabels[] = $breed['breed'];
    $breedData[] = (int)$breed['total'];
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-tags-fill"></i>

Cow Report

</h2>

<p>

Comprehensive herd summary and livestock records.

</p>

</div>

<!-- KPI CARDS -->

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-green">

<i class="bi bi-tags-fill"></i>

<h5>Total Cows</h5>

<h2><?= number_format($totalCows) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-blue">

<i class="bi bi-check-circle-fill"></i>

<h5>Active Cows</h5>

<h2><?= number_format($activeCows) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-orange">

<i class="bi bi-cash-stack"></i>

<h5>Sold Cows</h5>

<h2><?= number_format($soldCows) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-red">

<i class="bi bi-x-circle-fill"></i>

<h5>Dead Cows</h5>

<h2><?= number_format($deadCows) ?></h2>

</div>

</div>

</div>

<!-- REPORT ACTIONS -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-file-earmark-text-fill"></i>

Herd Register

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

Registered Cows

</h5>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Tag Number</th>

<th>Name</th>

<th>Breed</th>

<th>Gender</th>

<th>Date of Birth</th>

<th>Status</th>

<th>Registered On</th>

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

<strong><?= htmlspecialchars($row['tag_number']); ?></strong>

</td>

<td><?= htmlspecialchars($row['name']); ?></td>

<td><?= htmlspecialchars($row['breed']); ?></td>

<td>

<?php if($row['gender']=="Female"): ?>

<span class="badge bg-primary">

<i class="bi bi-gender-female"></i>

Female

</span>

<?php else: ?>

<span class="badge bg-secondary">

<i class="bi bi-gender-male"></i>

Male

</span>

<?php endif; ?>

</td>

<td>

<?= !empty($row['date_of_birth']) ? date("d M Y", strtotime($row['date_of_birth'])) : "-" ?>

</td>

<td>

<?php

switch($row['status'])
{

case "Active":

echo '<span class="badge bg-success">
<i class="bi bi-check-circle-fill"></i>
 Active
</span>';

break;

case "Sold":

echo '<span class="badge bg-warning text-dark">
<i class="bi bi-cash-stack"></i>
 Sold
</span>';

break;

case "Dead":

echo '<span class="badge bg-danger">
<i class="bi bi-x-circle-fill"></i>
 Dead
</span>';

break;

default:

echo '<span class="badge bg-secondary">'
.htmlspecialchars($row['status']).
'</span>';

}

?>

</td>

<td>

<?= date("d M Y", strtotime($row['created_at'])); ?>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="8" class="text-center text-muted py-4">

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No cow records found.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="7" class="text-end">

Total Registered Cows

</th>

<th>

<?= number_format($totalCows); ?>

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

            Breed Distribution

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <canvas id="breedChart" height="100"></canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const breedChart=document.getElementById("breedChart");

new Chart(breedChart,{

    type:'pie',

    data:{

        labels:<?= json_encode($breedLabels); ?>,

        datasets:[{

            data:<?= json_encode($breedData); ?>,

            backgroundColor:[

                '#198754',

                '#0d6efd',

                '#ffc107',

                '#dc3545',

                '#20c997',

                '#6f42c1',

                '#fd7e14',

                '#6610f2'

            ],

            borderWidth:2,

            borderColor:'#ffffff'

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

                text:'Distribution of Cows by Breed'

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