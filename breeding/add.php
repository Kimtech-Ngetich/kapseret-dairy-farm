<?php

$pageTitle = "Add Breeding Record";
$activePage = "breeding";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$message = "";

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id,tag_number,name
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

$breeding_date = $_POST['breeding_date'];

$breeding_method = mysqli_real_escape_string($conn,$_POST['breeding_method']);

$bull_name = mysqli_real_escape_string($conn,$_POST['bull_name']);

$semen_code = mysqli_real_escape_string($conn,$_POST['semen_code']);

$technician = mysqli_real_escape_string($conn,$_POST['technician']);

$pregnancy_check_date = !empty($_POST['pregnancy_check_date'])
? $_POST['pregnancy_check_date']
: NULL;

$pregnancy_status = $_POST['pregnancy_status'];

$expected_calving_date = !empty($_POST['expected_calving_date'])
? $_POST['expected_calving_date']
: NULL;

$remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

$insert = mysqli_query($conn,"
INSERT INTO breeding_records(

cow_id,

breeding_date,

breeding_method,

bull_name,

semen_code,

technician,

pregnancy_check_date,

pregnancy_status,

expected_calving_date,

remarks

)

VALUES(

'$cow_id',

'$breeding_date',

'$breeding_method',

'$bull_name',

'$semen_code',

'$technician',

".($pregnancy_check_date ? "'$pregnancy_check_date'" : "NULL").",

'$pregnancy_status',

".($expected_calving_date ? "'$expected_calving_date'" : "NULL").",

'$remarks'

)

");

if($insert)
{

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Added a breeding record')
");

$message='

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Breeding record saved successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>';

}
else
{

$message='

<div class="alert alert-danger">

Unable to save breeding record.

</div>';

}

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-pulse-fill"></i>

Add Breeding Record

</h2>

<p>

Record breeding, pregnancy and calving information.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-plus-circle-fill"></i>

New Breeding Record

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Select Cow

</label>

<select
name="cow_id"
class="form-select"
required>

<option value="">

-- Select Cow --

</option>

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option value="<?= $cow['id']; ?>">

<?= $cow['tag_number']; ?>

-

<?= $cow['name']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Breeding Date

</label>

<input
type="date"
id="breeding_date"
name="breeding_date"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Breeding Method

</label>

<select
name="breeding_method"
class="form-select"
required>

<option value="">

Choose Method

</option>

<option>

Artificial Insemination

</option>

<option>

Natural Mating

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Bull Name

</label>

<input
type="text"
name="bull_name"
class="form-control"
placeholder="Bull Name">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Semen Code

</label>

<input
type="text"
name="semen_code"
class="form-control"
placeholder="Optional">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Technician / Inseminator

</label>

<input
type="text"
name="technician"
class="form-control"
placeholder="Veterinary Officer">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Pregnancy Check Date

</label>

<input
type="date"
name="pregnancy_check_date"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Pregnancy Status

</label>

<select
name="pregnancy_status"
class="form-select">

<option selected>

Pending

</option>

<option>

Confirmed

</option>

<option>

Failed

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Expected Calving Date

</label>

<input
type="date"
id="expected_calving_date"
name="expected_calving_date"
class="form-control">

</div>

<div class="col-12 mb-4">

<label class="form-label">

Remarks

</label>

<textarea
name="remarks"
rows="4"
class="form-control"
placeholder="Additional notes..."></textarea>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-save"></i>

Save Breeding Record

</button>

</div>

</form>

</div>

</div>

</div>

</div>
<?php include "../includes/footer.php"; ?>

<script>

/*
|--------------------------------------------------------------------------
| AUTO CALCULATE EXPECTED CALVING DATE
|--------------------------------------------------------------------------
| Average gestation period of a dairy cow = 283 days
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function(){

    const breedingDate = document.getElementById("breeding_date");
    const calvingDate = document.getElementById("expected_calving_date");

    breedingDate.addEventListener("change", function(){

        if(this.value==="") return;

        let date = new Date(this.value);

        date.setDate(date.getDate() + 283);

        let year = date.getFullYear();

        let month = String(date.getMonth()+1).padStart(2,"0");

        let day = String(date.getDate()).padStart(2,"0");

        calvingDate.value = `${year}-${month}-${day}`;

    });

});

</script>

</body>

</html>