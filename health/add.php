<?php

$pageTitle = "Add Health Record";
$activePage = "health";

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

$cow_id=(int)$_POST['cow_id'];

$record_date=$_POST['record_date'];

$disease=mysqli_real_escape_string($conn,trim($_POST['disease']));

$symptoms=mysqli_real_escape_string($conn,trim($_POST['symptoms']));

$treatment=mysqli_real_escape_string($conn,trim($_POST['treatment']));

$veterinarian=mysqli_real_escape_string($conn,trim($_POST['veterinarian']));

$medicine=mysqli_real_escape_string($conn,trim($_POST['medicine']));

$cost=(float)$_POST['cost'];

$remarks=mysqli_real_escape_string($conn,trim($_POST['remarks']));

$insert=mysqli_query($conn,"
INSERT INTO health_records
(
cow_id,
record_date,
disease,
symptoms,
treatment,
veterinarian,
medicine,
cost,
remarks
)
VALUES
(
'$cow_id',
'$record_date',
'$disease',
'$symptoms',
'$treatment',
'$veterinarian',
'$medicine',
'$cost',
'$remarks'
)
");

if($insert)
{

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Added a health record')
");

$message='
<div class="alert alert-success alert-dismissible fade show">

Health record added successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>';

}
else
{

$message='
<div class="alert alert-danger">

Unable to save health record.

</div>';

}

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-pulse-fill"></i>

Add Health Record

</h2>

<p>

Record diseases, treatments and veterinary services.

</p>

</div>

<?= $message; ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-plus-circle"></i>

New Health Record

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Cow</label>

<select
name="cow_id"
class="form-select"
required>

<option value="">Select Cow</option>

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option value="<?= $cow['id']; ?>">

<?= $cow['tag_number']; ?> - <?= $cow['name']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Record Date</label>

<input
type="date"
name="record_date"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Disease</label>

<input
type="text"
name="disease"
class="form-control"
placeholder="e.g. Mastitis"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Medicine</label>

<input
type="text"
name="medicine"
class="form-control"
placeholder="Medicine administered">

</div>
<div class="col-md-12 mb-3">

    <label class="form-label">Symptoms</label>

    <textarea
        name="symptoms"
        class="form-control"
        rows="3"
        placeholder="Describe the observed symptoms..."></textarea>

</div>

<div class="col-md-12 mb-3">

    <label class="form-label">Treatment</label>

    <textarea
        name="treatment"
        class="form-control"
        rows="3"
        placeholder="Describe the treatment administered..."></textarea>

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">Veterinarian</label>

    <input
        type="text"
        name="veterinarian"
        class="form-control"
        placeholder="Veterinarian's Name">

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">Treatment Cost (KES)</label>

    <input
        type="number"
        name="cost"
        class="form-control"
        min="0"
        step="0.01"
        value="0.00">

</div>

<div class="col-md-12 mb-4">

    <label class="form-label">Remarks</label>

    <textarea
        name="remarks"
        class="form-control"
        rows="3"
        placeholder="Additional notes or recommendations..."></textarea>

</div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-between">

    <a href="index.php" class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Back to Health Records

    </a>

    <button
        type="submit"
        name="save"
        class="btn btn-success">

        <i class="bi bi-save"></i>

        Save Health Record

    </button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>