<?php

$pageTitle = "Delete Milk Record";
$activePage = "milk";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| VALIDATE RECORD ID
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: records.php");
    exit();
}

$id=(int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| LOAD RECORD
|--------------------------------------------------------------------------
*/

$query=mysqli_query($conn,"
SELECT
milk_records.*,
cows.tag_number,
cows.name

FROM milk_records

INNER JOIN cows
ON cows.id=milk_records.cow_id

WHERE milk_records.id='$id'

LIMIT 1
");

if(mysqli_num_rows($query)==0){
    header("Location: records.php");
    exit();
}

$row=mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| DELETE RECORD
|--------------------------------------------------------------------------
*/

if(isset($_POST['delete'])){

    $delete=mysqli_query($conn,"
    DELETE FROM milk_records
    WHERE id='$id'
    ");

    if($delete){

        mysqli_query($conn,"
        INSERT INTO activities(activity)
        VALUES(
        'Deleted milk record for cow {$row['tag_number']}'
        )
        ");

        header("Location: records.php?deleted=1");
        exit();

    }

}

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header bg-danger">

<h2>

<i class="bi bi-trash-fill"></i>

Delete Milk Record

</h2>

<p>

Confirm before permanently deleting this record.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-exclamation-triangle-fill text-danger"></i>

Confirm Deletion

</h5>

</div>

<div class="dashboard-panel-body">

<div class="alert alert-warning">

<strong>Warning!</strong>

This action cannot be undone.

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label fw-bold">

Cow Tag

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['tag_number']) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="form-label fw-bold">

Cow Name

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['name']) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="form-label fw-bold">

Production Date

</label>

<div class="form-control bg-light">

<?= date("d M Y",strtotime($row['production_date'])) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="form-label fw-bold">

Session

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['session']) ?>

</div>

</div>

<div class="col-md-6 mb-3">

<label class="form-label fw-bold">

Quantity

</label>

<div class="form-control bg-light">

<?= number_format($row['quantity'],2) ?> Litres

</div>

</div>

<form method="POST">

<hr class="my-4">

<div class="glass-card border border-danger">

<div class="card-body-custom text-center">

<i class="bi bi-trash-fill text-danger display-3 mb-3"></i>

<h4 class="text-danger">

Are you sure you want to delete this milk record?

</h4>

<p class="text-muted mb-0">

Deleting this record will permanently remove it from the system.
This action cannot be reversed.

</p>

</div>

</div>

<div class="mt-4 d-flex justify-content-between flex-wrap gap-2">

<a href="records.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<div>

<button
type="submit"
name="delete"
class="btn btn-danger">

<i class="bi bi-trash-fill"></i>

Delete Record

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>

</html>