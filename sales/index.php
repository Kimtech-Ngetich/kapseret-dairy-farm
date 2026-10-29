<?php

$pageTitle = "Sales Management";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| TODAY'S REVENUE
|--------------------------------------------------------------------------
*/

$todayRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total_amount),0) revenue
FROM sales
WHERE sale_date = CURDATE()
"));

/*
|--------------------------------------------------------------------------
| MONTH REVENUE
|--------------------------------------------------------------------------
*/

$monthRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total_amount),0) revenue
FROM sales
WHERE MONTH(sale_date)=MONTH(CURDATE())
AND YEAR(sale_date)=YEAR(CURDATE())
"));

/*
|--------------------------------------------------------------------------
| TODAY'S MILK SOLD
|--------------------------------------------------------------------------
*/

$todayMilk = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(quantity),0) litres
FROM sales
WHERE sale_date = CURDATE()
"));

/*
|--------------------------------------------------------------------------
| TOTAL SALES
|--------------------------------------------------------------------------
*/

$totalSales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM sales
"));

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-cash-coin"></i>

Sales Management

</h2>

<p>

Manage milk sales, revenue and customer transactions.

</p>

</div>

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="kpi-card kpi-green">

<h6>Revenue Today</h6>

<h2>KES <?= number_format($todayRevenue['revenue'],2) ?></h2>

<i class="bi bi-cash-stack"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="kpi-card kpi-blue">

<h6>This Month</h6>

<h2>KES <?= number_format($monthRevenue['revenue'],2) ?></h2>

<i class="bi bi-graph-up-arrow"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="kpi-card kpi-orange">

<h6>Milk Sold Today</h6>

<h2><?= number_format($todayMilk['litres'],2) ?> L</h2>

<i class="bi bi-droplet-half"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="kpi-card kpi-purple">

<h6>Total Transactions</h6>

<h2><?= $totalSales['total'] ?></h2>

<i class="bi bi-receipt"></i>

</div>

</div>

</div>
<!-- Revenue Chart -->

<div class="dashboard-panel mb-4">

    <div class="dashboard-panel-header">

        <h5>
            <i class="bi bi-graph-up-arrow"></i>
            Weekly Revenue Trend
        </h5>

    </div>

    <div class="dashboard-panel-body">

        <div class="chart-box">

            <canvas id="salesChart"></canvas>

        </div>

    </div>

</div>

<div class="row">

    <!-- Recent Sales -->

    <div class="col-lg-8">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <h5>

                    <i class="bi bi-clock-history"></i>

                    Recent Sales

                </h5>

                <a href="records.php" class="btn btn-success btn-sm">

                    View All

                </a>

            </div>

            <div class="dashboard-panel-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Customer</th>
                                <th>Date</th>
                                <th>Quantity</th>
                                <th>Total</th>

                            </tr>

                        </thead>

                        <tbody>

<?php

$recentSales=mysqli_query($conn,"
SELECT *
FROM sales
ORDER BY id DESC
LIMIT 5
");

if(mysqli_num_rows($recentSales)>0){

while($sale=mysqli_fetch_assoc($recentSales)){

?>

<tr>

<td><?= htmlspecialchars($sale['customer_name']) ?></td>

<td><?= date("d M Y",strtotime($sale['sale_date'])) ?></td>

<td><?= number_format($sale['quantity'],2) ?> L</td>

<td>KES <?= number_format($sale['total_amount'],2) ?></td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="4" class="text-center text-muted">

No sales recorded yet.

</td>

</tr>

<?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- Quick Actions -->

    <div class="col-lg-4">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <h5>

                    <i class="bi bi-lightning-fill"></i>

                    Quick Actions

                </h5>

            </div>

            <div class="dashboard-panel-body">

                <div class="d-grid gap-3">

                    <a href="add.php" class="btn btn-success">

                        <i class="bi bi-plus-circle"></i>

                        Record Sale

                    </a>

                    <a href="records.php" class="btn btn-primary">

                        <i class="bi bi-table"></i>

                        Sales Records

                    </a>

                    <a href="../reports/index.php" class="btn btn-warning">

                        <i class="bi bi-file-earmark-bar-graph"></i>

                        Sales Reports

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

<script>

const salesCtx=document.getElementById('salesChart');

new Chart(salesCtx,{

type:'line',

data:{

labels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],

datasets:[{

label:'Revenue (KES)',

data:[12000,18000,14500,22000,19500,25000,28000],

fill:true,

tension:.4,

borderWidth:3,

backgroundColor:'rgba(25,135,84,.15)',

borderColor:'#198754',

pointRadius:5,

pointHoverRadius:7

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

display:true

}

},

scales:{

y:{

beginAtZero:true

}

}

}

});

</script>

</body>

</html>