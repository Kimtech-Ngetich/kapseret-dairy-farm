<?php

$pageTitle = "Record Milk";
$activePage = "milk";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

$message = "";

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id, tag_number, name
FROM cows
WHERE status='Active'
ORDER BY tag_number ASC
");

/*
|--------------------------------------------------------------------------
| SAVE RECORD
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{

    $cow_id = (int)$_POST['cow_id'];

    $production_date = mysqli_real_escape_string(
        $conn,
        $_POST['production_date']
    );

    $session = mysqli_real_escape_string(
        $conn,
        $_POST['session']
    );

    $quantity = (float)$_POST['quantity'];

    /*
    ----------------------------------------------------------
    CHECK DUPLICATE RECORD
    ----------------------------------------------------------
    */

    $duplicate = mysqli_query($conn,"
        SELECT id
        FROM milk_records
        WHERE cow_id='$cow_id'
        AND production_date='$production_date'
        AND session='$session'
    ");

    if(mysqli_num_rows($duplicate)>0)
    {

        $message='
        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill"></i>

            Milk for this cow has already been recorded for this session.

        </div>';

    }
    else
    {

        $save = mysqli_query($conn,"
        INSERT INTO milk_records
        (
            cow_id,
            production_date,
            session,
            quantity
        )
        VALUES
        (
            '$cow_id',
            '$production_date',
            '$session',
            '$quantity'
        )
        ");

        if($save)
        {

            $cowInfo = mysqli_fetch_assoc(
                mysqli_query($conn,"
                SELECT tag_number
                FROM cows
                WHERE id='$cow_id'
                ")
            );

            mysqli_query($conn,"
            INSERT INTO activities(activity)
            VALUES(
                'Recorded $quantity L of milk for cow {$cowInfo['tag_number']}'
            )
            ");

            $message='
            <div class="alert alert-success">

                <i class="bi bi-check-circle-fill"></i>

                Milk production recorded successfully.

            </div>';

        }
        else
        {

            $message='
            <div class="alert alert-danger">

                <i class="bi bi-x-circle-fill"></i>

                Unable to save the record.

            </div>';

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

<i class="bi bi-droplet-fill"></i>

Record Milk Production

</h2>

<p>

Capture daily milk production for every cow.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-plus-circle"></i>

New Milk Record

</h5>

<a href="records.php" class="btn btn-outline-success">

<i class="bi bi-table"></i>

View Records

</a>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-lg-6 mb-4">

<label class="form-label">

Select Cow

</label>

<select
name="cow_id"
class="form-select"
required>

<option value="">

Choose Cow...

</option>

<?php
while($cow=mysqli_fetch_assoc($cows)){
?>

<option value="<?= $cow['id'] ?>">

<?= htmlspecialchars($cow['tag_number']) ?>

—

<?= htmlspecialchars($cow['name']) ?>

</option>

<?php
}
?>

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
value="<?= date('Y-m-d') ?>"
required>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label">

Session

</label>

<select
name="session"
class="form-select"
required>

<option value="Morning">

Morning

</option>

<option value="Evening">

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
placeholder="0.00"
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

<i class="bi bi-info-circle-fill text-success"></i>

Recording Guidelines

</h5>

<ul class="mb-0">

<li>Record milk immediately after each milking session.</li>

<li>Each cow can only have one record per session per day.</li>

<li>Ensure the quantity entered is measured in litres.</li>

<li>Only active cows appear in the selection list.</li>

</ul>

</div>

</div>

</div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-between">

<a href="records.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Records

</a>

<div>

<button type="reset" class="btn btn-warning me-2">

<i class="bi bi-arrow-clockwise"></i>

Reset

</button>

<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-save-fill"></i>

Save Record

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

const qty=document.querySelector("input[name='quantity']");

if(qty){

qty.focus();

}

/*
|--------------------------------------------------------------------------
| Prevent future dates
|--------------------------------------------------------------------------
*/

const dateInput=document.querySelector("input[name='production_date']");

if(dateInput){

const today=new Date().toISOString().split('T')[0];

dateInput.max=today;

}

});

</script>

</body>

</html>