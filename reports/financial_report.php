<?php

$pageTitle = "Financial Report";
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
| FINANCIAL SUMMARY
|--------------------------------------------------------------------------
*/

$summary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

COUNT(*) AS total_sales,

IFNULL(SUM(quantity),0) AS total_milk,

IFNULL(SUM(total_amount),0) AS total_revenue,

IFNULL(AVG(total_amount),0) AS average_sale

FROM sales

WHERE sale_date
BETWEEN '$from' AND '$to'
"));

$totalSales = (int)$summary['total_sales'];
$totalMilk = (float)$summary['total_milk'];
$totalRevenue = (float)$summary['total_revenue'];
$averageSale = (float)$summary['average_sale'];

$averageDailyRevenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

IFNULL(AVG(daily_total),0) AS avg_revenue

FROM(

SELECT
sale_date,
SUM(total_amount) AS daily_total

FROM sales

WHERE sale_date
BETWEEN '$from' AND '$to'

GROUP BY sale_date

) daily
"));

$averageDailyRevenue = (float)$averageDailyRevenue['avg_revenue'];

/*
|--------------------------------------------------------------------------
| REPORT TABLE
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *

FROM sales

WHERE sale_date
BETWEEN '$from' AND '$to'

ORDER BY sale_date DESC
");

/*
|--------------------------------------------------------------------------
| CHART DATA
|--------------------------------------------------------------------------
*/

$chartQuery = mysqli_query($conn,"
SELECT

sale_date,

SUM(total_amount) AS revenue

FROM sales

WHERE sale_date
BETWEEN '$from' AND '$to'

GROUP BY sale_date

ORDER BY sale_date
");

$labels = [];
$data = [];

while($row=mysqli_fetch_assoc($chartQuery))
{
    $labels[] = date("d M",strtotime($row['sale_date']));
    $data[] = (float)$row['revenue'];
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-graph-up-arrow"></i>

Financial Report

</h2>

<p>

Executive financial summary and revenue analysis for Kapketet Dairy Farm.

</p>

</div>

<!-- FILTER PANEL -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-funnel-fill"></i>

Financial Report Filter

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
href="financial_report.php"
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

<div class="stat-card bg-success">

<i class="bi bi-cash-stack"></i>

<h5>Total Revenue</h5>

<h2>KSh <?= number_format($totalRevenue,2) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-primary">

<i class="bi bi-receipt-cutoff"></i>

<h5>Total Sales</h5>

<h2><?= number_format($totalSales) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-info">

<i class="bi bi-droplet-fill"></i>

<h5>Total Milk Sold</h5>

<h2><?= number_format($totalMilk,2) ?> L</h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-warning">

<i class="bi bi-calendar-week-fill"></i>

<h5>Avg Daily Revenue</h5>

<h2>KSh <?= number_format($averageDailyRevenue,2) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-danger">

<i class="bi bi-graph-up"></i>

<h5>Average Sale</h5>

<h2>KSh <?= number_format($averageSale,2) ?></h2>

</div>

</div>

</div>

<!-- REPORT ACTIONS -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-file-earmark-bar-graph-fill"></i>

Financial Transactions

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

            Financial Transactions

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-success">

                    <tr>

                        <th>#</th>

                        <th>Sale Date</th>

                        <th>Customer</th>

                        <th>Quantity (L)</th>

                        <th>Price/Litre</th>

                        <th>Total Amount</th>

                        <th>Payment Method</th>

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

                            <?= date("d M Y", strtotime($row['sale_date'])); ?>

                        </td>

                        <td>

                            <strong><?= htmlspecialchars($row['customer_name']); ?></strong>

                        </td>

                        <td>

                            <?= number_format($row['quantity'],2); ?> L

                        </td>

                        <td>

                            KSh <?= number_format($row['price_per_litre'],2); ?>

                        </td>

                        <td>

                            <strong class="text-success">

                                KSh <?= number_format($row['total_amount'],2); ?>

                            </strong>

                        </td>

                        <td>

                            <?php

                            switch($row['payment_method'])
                            {

                                case "Cash":

                                    echo '<span class="badge bg-success">
                                    <i class="bi bi-cash"></i> Cash
                                    </span>';

                                    break;

                                case "Mpesa":

                                    echo '<span class="badge bg-primary">
                                    <i class="bi bi-phone"></i> M-Pesa
                                    </span>';

                                    break;

                                case "Bank":

                                    echo '<span class="badge bg-info text-dark">
                                    <i class="bi bi-bank"></i> Bank
                                    </span>';

                                    break;

                                default:

                                    echo '<span class="badge bg-secondary">' .
                                    htmlspecialchars($row['payment_method']) .
                                    '</span>';

                            }

                            ?>

                        </td>

                    </tr>

                <?php

                    endwhile;

                else:

                ?>

                    <tr>

                        <td colspan="7" class="text-center text-muted py-4">

                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                            No financial transactions found for the selected period.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

                <tfoot class="table-light">

                    <tr>

                        <th colspan="5" class="text-end">

                            Total Revenue

                        </th>

                        <th class="text-success">

                            KSh <?= number_format($totalRevenue,2); ?>

                        </th>

                        <th></th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>
<div class="dashboard-panel mt-4">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-bar-chart-line-fill"></i>

            Revenue Trend Analysis

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <canvas id="financialChart" height="100"></canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const financialChart=document.getElementById("financialChart");

new Chart(financialChart,{

type:'line',

data:{

labels:<?= json_encode($labels); ?>,

datasets:[{

label:'Revenue (KSh)',

data:<?= json_encode($data); ?>,

borderColor:'#198754',

backgroundColor:'rgba(25,135,84,0.15)',

fill:true,

borderWidth:3,

tension:0.4,

pointRadius:5,

pointHoverRadius:7,

pointBackgroundColor:'#198754'

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

display:true,

position:'top'

},

title:{

display:true,

text:'Daily Revenue Trend'

}

},

scales:{

y:{

beginAtZero:true,

title:{

display:true,

text:'Revenue (KSh)'

}

},

x:{

title:{

display:true,

text:'Date'

}

}

}

}

});

</script>

<style>

#financialChart{

min-height:350px;

}

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

width:100% !important;

}

.dashboard-panel{

box-shadow:none;

border:1px solid #ddd;

page-break-inside:avoid;

margin-bottom:20px;

}

table{

font-size:12px;

}

canvas{

max-width:100% !important;

}

}

</style>

<?php include "../includes/footer.php"; ?>

</body>
</html>