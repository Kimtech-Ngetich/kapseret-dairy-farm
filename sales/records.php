<?php

$pageTitle = "Sales Records";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| SEARCH & FILTER
|--------------------------------------------------------------------------
*/

$search = $_GET['search'] ?? "";
$payment = $_GET['payment'] ?? "";

$where = "WHERE 1=1";

if($search != "")
{
    $search = mysqli_real_escape_string($conn,$search);

    $where .= "
        AND customer_name LIKE '%$search%'
    ";
}

if($payment != "")
{
    $payment = mysqli_real_escape_string($conn,$payment);

    $where .= "
        AND payment_method='$payment'
    ";
}

/*
|--------------------------------------------------------------------------
| SALES
|--------------------------------------------------------------------------
*/

$sales = mysqli_query($conn,"
SELECT *
FROM sales
$where
ORDER BY sale_date DESC,id DESC
");

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$summary = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT

COUNT(*) total_sales,

IFNULL(SUM(quantity),0) total_litres,

IFNULL(SUM(total_amount),0) total_revenue

FROM sales
"));

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-receipt"></i>

Sales Records

</h2>

<p>

Manage all milk sales transactions.

</p>

</div>


<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-receipt"></i>

Sales Records

</h2>

<p>

Manage all milk sales transactions.

</p>

</div>

<!-- INSERT THE ALERTS HERE -->

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Sale deleted successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

Unable to delete sale.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<!-- KPI Cards -->

<div class="row mb-4">

    <div class="col-md-4">

        <div class="kpi-card kpi-green">

            <h6>Total Revenue</h6>

            <h2>KES <?= number_format($summary['total_revenue'],2) ?></h2>

        </div>

    </div>

    <div class="col-md-4">

        <div class="kpi-card kpi-blue">

            <h6>Total Litres Sold</h6>

            <h2><?= number_format($summary['total_litres'],2) ?> L</h2>

        </div>

    </div>

    <div class="col-md-4">

        <div class="kpi-card kpi-purple">

            <h6>Total Transactions</h6>

            <h2><?= $summary['total_sales'] ?></h2>

        </div>

    </div>

</div>

<!-- Filter Panel -->

<div class="dashboard-panel mb-4">

<div class="dashboard-panel-body">

<form method="GET">

<div class="row">

<div class="col-md-5">

<input
type="text"
name="search"
class="form-control"
placeholder="Search customer..."
value="<?= htmlspecialchars($search) ?>">

</div>

<div class="col-md-3">

<select
name="payment"
class="form-select">

<option value="">All Payment Methods</option>

<option value="Cash"
<?= $payment=="Cash"?"selected":"" ?>>

Cash

</option>

<option value="Mpesa"
<?= $payment=="Mpesa"?"selected":"" ?>>

Mpesa

</option>

<option value="Bank"
<?= $payment=="Bank"?"selected":"" ?>>

Bank

</option>

</select>

</div>

<div class="col-md-4">

<button class="btn btn-success">

<i class="bi bi-search"></i>

Search

</button>

<a href="records.php"
class="btn btn-secondary">

Reset

</a>

<a href="add.php"
class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

New Sale

</a>

</div>

</div>

</form>

</div>

</div>
<!-- Sales Records Table -->

<div class="dashboard-panel">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-table"></i>

            Sales Transactions

        </h5>

        <span class="badge bg-success">

            <?= mysqli_num_rows($sales); ?> Records

        </span>

    </div>

    <div class="dashboard-panel-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Customer</th>
                        <th>Sale Date</th>
                        <th>Quantity (L)</th>
                        <th>Price/Litre</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th width="170">Actions</th>

                    </tr>

                </thead>

                <tbody>

<?php

$no = 1;

if(mysqli_num_rows($sales) > 0):

while($row = mysqli_fetch_assoc($sales)):

?>

<tr>

<td><?= $no++ ?></td>

<td>

<strong><?= htmlspecialchars($row['customer_name']) ?></strong>

</td>

<td>

<?= date("d M Y", strtotime($row['sale_date'])) ?>

</td>

<td>

<?= number_format($row['quantity'],2) ?>

</td>

<td>

KES <?= number_format($row['price_per_litre'],2) ?>

</td>

<td>

<strong>

KES <?= number_format($row['total_amount'],2) ?>

</strong>

</td>

<td>

<?php

switch($row['payment_method']){

case "Cash":

echo '<span class="badge bg-success">Cash</span>';

break;

case "Mpesa":

echo '<span class="badge bg-primary">Mpesa</span>';

break;

case "Bank":

echo '<span class="badge bg-info text-dark">Bank</span>';

break;

default:

echo '<span class="badge bg-secondary">'.$row['payment_method'].'</span>';

}

?>

</td>

<td>

<a href="view.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-primary">

<i class="bi bi-eye"></i>

</a>

<a href="edit.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-warning">

<i class="bi bi-pencil-square"></i>

</a>

<a href="delete.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this sale record?');">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-receipt-cutoff display-5 text-muted"></i>

<br><br>

No sales records found.

</td>

</tr>

<?php endif; ?>

                </tbody>

                <tfoot>

<tr class="table-success">

<th colspan="5">

Grand Total Revenue

</th>

<th>

KES <?= number_format($summary['total_revenue'],2) ?>

</th>

<th colspan="2">

<?= number_format($summary['total_litres'],2) ?> Litres Sold

</th>

</tr>

                </tfoot>

            </table>

        </div>

    </div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>

</html>