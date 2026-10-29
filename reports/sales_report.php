<?php

$pageTitle = "Sales Report";
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
$to   = $_GET['to'] ?? date('Y-m-d');

/*
|--------------------------------------------------------------------------
| SALES RECORDS
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
| SUMMARY
|--------------------------------------------------------------------------
*/

$summary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

COUNT(*) total_sales,

IFNULL(SUM(total_amount),0) total_revenue,

IFNULL(AVG(total_amount),0) average_sale,

IFNULL(SUM(quantity),0) total_litres

FROM sales

WHERE sale_date
BETWEEN '$from' AND '$to'

"));

$totalSales = $summary['total_sales'];

$totalRevenue = $summary['total_revenue'];

$averageSale = $summary['average_sale'];

$totalLitres = $summary['total_litres'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-cash-stack"></i>

Sales Report

</h2>

<p>

Analyze milk sales, revenue performance and transaction history.

</p>

</div>

<!-- FILTER PANEL -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-funnel-fill"></i>

Report Filters

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
href="sales_report.php"
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

<i class="bi bi-currency-dollar"></i>

<h5>Total Revenue</h5>

<h2>KSh <?= number_format($totalRevenue,2) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-blue">

<i class="bi bi-receipt-cutoff"></i>

<h5>Total Sales</h5>

<h2><?= number_format($totalSales) ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-orange">

<i class="bi bi-droplet-fill"></i>

<h5>Milk Sold</h5>

<h2><?= number_format($totalLitres,2) ?> L</h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="stat-card bg-red">

<i class="bi bi-graph-up-arrow"></i>

<h5>Average Sale</h5>

<h2>KSh <?= number_format($averageSale,2) ?></h2>

</div>

</div>

</div>
<div class="dashboard-panel">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-table"></i>

Sales Transactions

</h5>

<button
class="btn btn-outline-success btn-sm"
onclick="window.print()">

<i class="bi bi-printer"></i>

Print Report

</button>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Date</th>

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

if(mysqli_num_rows($query)>0):

while($row=mysqli_fetch_assoc($query)):

?>

<tr>

<td><?= $no++; ?></td>

<td>

<?= date("d M Y",strtotime($row['sale_date'])); ?>

</td>

<td>

<strong>

<?= htmlspecialchars($row['customer_name']); ?>

</strong>

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
<i class="bi bi-cash"></i>
 Cash
</span>';

break;

case "Mpesa":

echo '<span class="badge bg-primary">
<i class="bi bi-phone"></i>
 M-Pesa
</span>';

break;

case "Bank":

echo '<span class="badge bg-info">
<i class="bi bi-bank"></i>
 Bank
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

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No sales records found for the selected period.

</td>

</tr>

<?php endif; ?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="5" class="text-end">

Total Revenue

</th>

<th colspan="2" class="text-success">

KSh <?= number_format($totalRevenue,2); ?>

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
| SALES TREND CHART DATA
|--------------------------------------------------------------------------
*/

$chartQuery = mysqli_query($conn,"
SELECT
    sale_date,
    SUM(total_amount) AS revenue
FROM sales
WHERE sale_date BETWEEN '$from' AND '$to'
GROUP BY sale_date
ORDER BY sale_date ASC
");

$labels = [];
$data = [];

while($chart = mysqli_fetch_assoc($chartQuery))
{
    $labels[] = date("d M", strtotime($chart['sale_date']));
    $data[] = (float)$chart['revenue'];
}

?>

<div class="dashboard-panel mt-4">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-graph-up-arrow"></i>

            Daily Revenue Trend

        </h5>

    </div>

    <div class="dashboard-panel-body">

        <canvas id="salesChart" height="100"></canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const salesChart=document.getElementById('salesChart');

new Chart(salesChart,{

    type:'bar',

    data:{

        labels:<?= json_encode($labels); ?>,

        datasets:[{

            label:'Revenue (KSh)',

            data:<?= json_encode($data); ?>,

            backgroundColor:'rgba(25,135,84,.80)',

            borderColor:'#198754',

            borderWidth:2,

            borderRadius:8

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

                    text:'Revenue (KSh)'

                }

            },

            x:{

                title:{

                    display:true,

                    text:'Sale Date'

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