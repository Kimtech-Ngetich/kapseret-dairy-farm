<?php

$pageTitle = "Reports & Analytics";
$activePage = "reports";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| KPI CARDS
|--------------------------------------------------------------------------
*/

$totalCows = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM cows
"))['total'];

$activeCows = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM cows
WHERE status='Active'
"))['total'];

$milkToday = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(quantity),0) total
FROM milk_records
WHERE production_date = CURDATE()
"))['total'];

$todayRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total_amount),0) total
FROM sales
WHERE sale_date = CURDATE()
"))['total'];

$pregnantCows = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM breeding_records
WHERE pregnancy_status='Confirmed'
AND calving_status='Not Calved'
"))['total'];

$sickCows = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM health_records
WHERE record_date = CURDATE()
"))['total'];

$activities = mysqli_query($conn,"
SELECT *
FROM activities
ORDER BY id DESC
LIMIT 8
");

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-bar-chart-fill"></i>

Reports & Analytics

</h2>

<p>

Farm performance insights and management reports.

</p>

</div>

<div class="row">

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-green">

<i class="bi bi-tags-fill"></i>

<h5>Total Cows</h5>

<h2><?= $totalCows ?></h2>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-blue">

<i class="bi bi-check-circle-fill"></i>

<h5>Active Cows</h5>

<h2><?= $activeCows ?></h2>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-orange">

<i class="bi bi-droplet-fill"></i>

<h5>Milk Today</h5>

<h2><?= number_format($milkToday,1) ?></h2>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-red">

<i class="bi bi-cash-stack"></i>

<h5>Revenue</h5>

<h2>KSh <?= number_format($todayRevenue,2) ?></h2>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-primary">

<i class="bi bi-heart-fill"></i>

<h5>Pregnant</h5>

<h2><?= $pregnantCows ?></h2>

</div>

</div>

<div class="col-lg-2 col-md-4 col-sm-6 mb-4">

<div class="stat-card bg-warning">

<i class="bi bi-hospital-fill"></i>

<h5>Sick Today</h5>

<h2><?= $sickCows ?></h2>

</div>

</div>

</div>
<div class="row">

    <!-- Milk Production Chart -->

    <div class="col-lg-8 mb-4">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <h5>

                    <i class="bi bi-bar-chart-line-fill"></i>

                    Milk Production Trend

                </h5>

            </div>

            <div class="dashboard-panel-body">

                <canvas id="milkChart" height="100"></canvas>

            </div>

        </div>

    </div>

    <!-- Recent Activities -->

    <div class="col-lg-4 mb-4">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <h5>

                    <i class="bi bi-clock-history"></i>

                    Recent Activities

                </h5>

            </div>

            <div class="dashboard-panel-body">

                <div class="list-group list-group-flush">

                <?php

                if(mysqli_num_rows($activities)>0)
                {

                    while($activity=mysqli_fetch_assoc($activities))
                    {

                ?>

                    <div class="list-group-item border-0 px-0">

                        <i class="bi bi-check-circle-fill text-success me-2"></i>

                        <?= htmlspecialchars($activity['activity']); ?>

                        <br>

                        <small class="text-muted">

                            <?= date("d M Y H:i",strtotime($activity['created_at'])); ?>

                        </small>

                    </div>

                <?php

                    }

                }
                else
                {

                    echo "<p class='text-muted mb-0'>No recent activities.</p>";

                }

                ?>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- QUICK REPORTS -->

<div class="dashboard-panel">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-file-earmark-bar-graph-fill"></i>

            Quick Reports

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <div class="row g-3">

            <div class="col-lg-2 col-md-4">

                <a href="milk_report.php" class="btn btn-success w-100">

                    <i class="bi bi-droplet-fill"></i><br>

                    Milk

                </a>

            </div>

            <div class="col-lg-2 col-md-4">

                <a href="sales_report.php" class="btn btn-primary w-100">

                    <i class="bi bi-cash-stack"></i><br>

                    Sales

                </a>

            </div>

            <div class="col-lg-2 col-md-4">

                <a href="cows_report.php" class="btn btn-info w-100 text-white">

                    <i class="bi bi-tags-fill"></i><br>

                    Cows

                </a>

            </div>

            <div class="col-lg-2 col-md-4">

                <a href="health_report.php" class="btn btn-warning w-100">

                    <i class="bi bi-heart-pulse-fill"></i><br>

                    Health

                </a>

            </div>

            <div class="col-lg-2 col-md-4">

                <a href="breeding_report.php" class="btn btn-secondary w-100">

                    <i class="bi bi-heart-fill"></i><br>

                    Breeding

                </a>

            </div>

            <div class="col-lg-2 col-md-4">

                <a href="financial_report.php" class="btn btn-dark w-100">

                    <i class="bi bi-graph-up-arrow"></i><br>

                    Financial

                </a>

            </div>

        </div>

    </div>

</div>
<?php

/*
|--------------------------------------------------------------------------
| MILK PRODUCTION CHART DATA (LAST 7 DAYS)
|--------------------------------------------------------------------------
*/

$chartQuery = mysqli_query($conn,"
SELECT
    production_date,
    SUM(quantity) AS total_milk
FROM milk_records
WHERE production_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
GROUP BY production_date
ORDER BY production_date ASC
");

$chartLabels = [];
$chartData = [];

while($chart = mysqli_fetch_assoc($chartQuery))
{
    $chartLabels[] = date("D", strtotime($chart['production_date']));
    $chartData[] = (float)$chart['total_milk'];
}

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx=document.getElementById('milkChart');

new Chart(ctx,{

type:'line',

data:{

labels:<?= json_encode($chartLabels); ?>,

datasets:[{

label:'Milk Produced (Litres)',

data:<?= json_encode($chartData); ?>,

borderColor:'#198754',

backgroundColor:'rgba(25,135,84,0.15)',

fill:true,

tension:.4,

borderWidth:3,

pointRadius:5,

pointBackgroundColor:'#198754'

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

text:'Day'

}

}

}

}

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>