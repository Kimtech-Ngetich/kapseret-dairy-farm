<?php

$pageTitle = "View Sale";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

if(!isset($_GET['id']))
{
    header("Location: records.php");
    exit;
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT
sales.*,
cows.tag_number,
cows.name AS cow_name,
cows.breed
FROM sales
LEFT JOIN cows
ON sales.cow_id = cows.id
WHERE sales.id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: records.php");
    exit;
}

$sale = mysqli_fetch_assoc($query);

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-receipt"></i>

Sale Details

</h2>

<p>

View complete sales transaction information.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-info-circle"></i>

Transaction Information

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Customer Name

</label>

<div class="form-control">

<?= htmlspecialchars($sale['customer_name']) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Sale Date

</label>

<div class="form-control">

<?= date("d M Y",strtotime($sale['sale_date'])) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Cow

</label>

<div class="form-control">

<?= $sale['tag_number']; ?>

-

<?= $sale['cow_name']; ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Breed

</label>

<div class="form-control">

<?= $sale['breed']; ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Quantity Sold

</label>

<div class="form-control">

<?= number_format($sale['quantity'],2); ?> Litres

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Price Per Litre

</label>

<div class="form-control">

KES <?= number_format($sale['price_per_litre'],2); ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Total Amount

</label>

<div class="form-control bg-success text-white fw-bold">

KES <?= number_format($sale['total_amount'],2); ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold text-success">

Payment Method

</label>

<div class="form-control">

<?= htmlspecialchars($sale['payment_method']); ?>

</div>

</div>

<div class="col-12 mb-4">

<label class="fw-bold text-success">

Notes

</label>

<div class="form-control" style="min-height:120px;">

<?= !empty($sale['notes']) ? nl2br(htmlspecialchars($sale['notes'])) : "No notes provided."; ?>

</div>

</div>

<div class="col-12">

<a href="edit.php?id=<?= $sale['id']; ?>"
class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit Sale

</a>

<a href="delete.php?id=<?= $sale['id']; ?>"
class="btn btn-danger">

<i class="bi bi-trash"></i>

Delete

</a>

<a href="records.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>