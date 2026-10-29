<?php

$pageTitle = "View Feed Usage";
$activePage = "feed";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| VALIDATE ID
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| FETCH RECORD
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT

fu.*,

c.tag_number,

c.name AS cow_name,

fi.feed_name,

fi.feed_type,

fi.unit

FROM feed_usage fu

INNER JOIN cows c
ON fu.cow_id=c.id

INNER JOIN feed_inventory fi
ON fu.feed_id=fi.id

WHERE fu.id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php?error=1");
    exit;
}

$record = mysqli_fetch_assoc($query);

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-eye-fill"></i>

Feed Usage Details

</h2>

<p>

View complete information about the selected feed usage record.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-basket-fill"></i>

Feed Usage Information

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-3">

<strong>Cow Tag</strong>

<p><?= htmlspecialchars($record['tag_number']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Cow Name</strong>

<p><?= htmlspecialchars($record['cow_name']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Feed Name</strong>

<p><?= htmlspecialchars($record['feed_name']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Feed Type</strong>

<p><?= htmlspecialchars($record['feed_type']); ?></p>

</div>

<div class="col-md-6 mb-3">

<strong>Quantity Used</strong>

<p>

<?= number_format($record['quantity'],2); ?>

<?= htmlspecialchars($record['unit']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<strong>Feeding Time</strong>

<p>

<span class="badge bg-primary">

<?= htmlspecialchars($record['feeding_time']); ?>

</span>

</p>

</div>

<div class="col-md-6 mb-3">

<strong>Feeding Date</strong>

<p>

<?= date("d M Y",strtotime($record['feeding_date'])); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<strong>Recorded On</strong>

<p>

<?= date("d M Y H:i",strtotime($record['created_at'])); ?>

</p>

</div>

<div class="col-md-12 mb-3">

<strong>Remarks</strong>

<div class="border rounded p-3 bg-light">

<?= !empty($record['remarks'])

? nl2br(htmlspecialchars($record['remarks']))

: '<span class="text-muted">No remarks provided.</span>'; ?>

</div>

</div>

</div>

<hr>

<div class="text-end">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<a href="edit.php?id=<?= $record['id']; ?>" class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit

</a>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>

</html>