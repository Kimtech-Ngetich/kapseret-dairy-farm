<?php

$pageTitle = "Edit Milk Record";
$activePage = "milk";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| VALIDATE RECORD ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: records.php");
    exit();
}

$id = (int)$_GET['id'];

$message = "";

/*
|--------------------------------------------------------------------------
| LOAD RECORD
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
SELECT *
FROM milk_records
WHERE id='$id'
LIMIT 1
");

if(mysqli_num_rows($query)==0){
    header("Location: records.php");
    exit();
}

$record=mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows=mysqli_query($conn,"
SELECT id,tag_number,name
FROM cows
WHERE status='Active'
ORDER BY tag_number
");

/*
|--------------------------------------------------------------------------
| UPDATE RECORD
|--------------------------------------------------------------------------
*/

if(isset($_POST['update'])){

    $cow_id=(int)$_POST['cow_id'];

    $production_date=mysqli_real_escape_string(
        $conn,
        $_POST['production_date']
    );

    $session=mysqli_real_escape_string(
        $conn,
        $_POST['session']
    );

    $quantity=(float)$_POST['quantity'];

    /*
    ------------------------------------------------------------
    CHECK DUPLICATES
    ------------------------------------------------------------
    */

    $duplicate=mysqli_query($conn,"
    SELECT id
    FROM milk_records
    WHERE cow_id='$cow_id'
    AND production_date='$production_date'
    AND session='$session'
    AND id<>'$id'
    ");

    if(mysqli_num_rows($duplicate)>0){

        $message='
        <div class="alert alert-danger">

        <i class="bi bi-exclamation-triangle-fill"></i>

        Another milk record already exists for this cow and session.

        </div>';

    }else{

        $update=mysqli_query($conn,"
        UPDATE milk_records
        SET

        cow_id='$cow_id',

        production_date='$production_date',

        session='$session',

        quantity='$quantity'

        WHERE id='$id'
        ");

        if($update){

            $cow=mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT tag_number
            FROM cows
            WHERE id='$cow_id'
            "));

            mysqli_query($conn,"
            INSERT INTO activities(activity)
            VALUES(
            'Updated milk record for cow {$cow['tag_number']}'
            )
            ");

            $message='
            <div class="alert alert-success">

            <i class="bi bi-check-circle-fill"></i>

            Milk record updated successfully.

            </div>';

            $query=mysqli_query($conn,"
            SELECT *
            FROM milk_records
            WHERE id='$id'
            ");

            $record=mysqli_fetch_assoc($query);

        }

    }

}

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Milk Record

</h2>

<p>

Update milk production information.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-droplet-half"></i>

Milk Production Details

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-lg-6 mb-4">

<label class="form-label">

Cow

</label>

<select
name="cow_id"
class="form-select"
required>

<?php
while($cow=mysqli_fetch_assoc($cows)){
?>

<option
value="<?= $cow['id'] ?>"
<?= ($record['cow_id']==$cow['id'])?'selected':''; ?>>

<?= htmlspecialchars($cow['tag_number']) ?>

—

<?= htmlspecialchars($cow['name']) ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label">

Production Date

</label>

<input
type="date"
name="production_date"
class="form-control"
value="<?= $record['production_date'] ?>"
required>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label">

Session

</label>

<select
name="session"
class="form-select">

<option
value="Morning"
<?=($record['session']=="Morning")?"selected":"";?>>

Morning

</option>

<option
value="Evening"
<?=($record['session']=="Evening")?"selected":"";?>>

Evening

</option>

</select>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label">

Quantity (Litres)

</label>

<div class="input-group">

<input
type="number"
step="0.01"
min="0"
name="quantity"
class="form-control"
value="<?= $record['quantity'] ?>"
required>

<span class="input-group-text">

L

</span>

</div>

</div>
<div class="col-12">

<div class="glass-card">

<div class="card-body-custom">

<h5 class="mb-3">

<i class="bi bi-lightbulb-fill text-warning"></i>

Editing Guidelines

</h5>

<ul class="mb-0">

<li>Ensure the production date is correct before saving.</li>

<li>Each cow can only have one milk record per session per day.</li>

<li>Update the quantity using the actual measured litres.</li>

<li>Only active cows are available for selection.</li>

</ul>

</div>

</div>

</div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-between flex-wrap gap-2">

<a href="records.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Records

</a>

<div>

<button
type="reset"
class="btn btn-warning me-2">

<i class="bi bi-arrow-clockwise"></i>

Reset

</button>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Update Record

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

<script>

document.addEventListener("DOMContentLoaded",function(){

const dateInput=document.querySelector("input[name='production_date']");

if(dateInput){

const today=new Date().toISOString().split('T')[0];

dateInput.max=today;

}

});

</script>

</body>

</html>