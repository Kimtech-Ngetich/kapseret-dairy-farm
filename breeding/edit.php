<?php

$pageTitle = "Edit Breeding Record";
$activePage = "breeding";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$message="";

$cows=mysqli_query($conn,"
SELECT id,tag_number,name
FROM cows
WHERE status='Active'
ORDER BY tag_number
");

$query=mysqli_query($conn,"
SELECT *
FROM breeding_records
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php");
    exit();
}

$row=mysqli_fetch_assoc($query);

if(isset($_POST['update']))
{

$cow_id=$_POST['cow_id'];

$breeding_date=$_POST['breeding_date'];

$breeding_method=$_POST['breeding_method'];

$bull_name=mysqli_real_escape_string($conn,$_POST['bull_name']);

$semen_code=mysqli_real_escape_string($conn,$_POST['semen_code']);

$technician=mysqli_real_escape_string($conn,$_POST['technician']);

$pregnancy_check_date=!empty($_POST['pregnancy_check_date'])
? "'".$_POST['pregnancy_check_date']."'" : "NULL";

$pregnancy_status=$_POST['pregnancy_status'];

$expected_calving_date=!empty($_POST['expected_calving_date'])
? "'".$_POST['expected_calving_date']."'" : "NULL";

$calving_status=$_POST['calving_status'];

$actual_calving_date=!empty($_POST['actual_calving_date'])
? "'".$_POST['actual_calving_date']."'" : "NULL";

$calves_born=(int)$_POST['calves_born'];

$calf_gender=mysqli_real_escape_string($conn,$_POST['calf_gender']);

$remarks=mysqli_real_escape_string($conn,$_POST['remarks']);

$update=mysqli_query($conn,"
UPDATE breeding_records SET

cow_id='$cow_id',

breeding_date='$breeding_date',

breeding_method='$breeding_method',

bull_name='$bull_name',

semen_code='$semen_code',

technician='$technician',

pregnancy_check_date=$pregnancy_check_date,

pregnancy_status='$pregnancy_status',

expected_calving_date=$expected_calving_date,

calving_status='$calving_status',

actual_calving_date=$actual_calving_date,

calves_born='$calves_born',

calf_gender='$calf_gender',

remarks='$remarks'

WHERE id='$id'
");

if($update)
{

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Updated breeding record')
");

header("Location:view.php?id=".$id."&updated=1");
exit();

}

$message="<div class='alert alert-danger'>
Unable to update record.
</div>";

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Breeding Record

</h2>

<p>

Update breeding, pregnancy and calving information.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-pencil-fill"></i>

Update Breeding Record

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Cow

</label>

<select
name="cow_id"
class="form-select"
required>

<?php

mysqli_data_seek($cows,0);

while($cow=mysqli_fetch_assoc($cows)):

?>

<option
value="<?= $cow['id']; ?>"
<?= ($cow['id']==$row['cow_id']) ? 'selected' : ''; ?>>

<?= htmlspecialchars($cow['tag_number']); ?>

-

<?= htmlspecialchars($cow['name']); ?>

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
value="<?= $row['breeding_date']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Breeding Method

</label>

<select
name="breeding_method"
class="form-select">

<option <?= ($row['breeding_method']=="Artificial Insemination") ? "selected" : ""; ?>>

Artificial Insemination

</option>

<option <?= ($row['breeding_method']=="Natural Mating") ? "selected" : ""; ?>>

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
value="<?= htmlspecialchars($row['bull_name']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Semen Code

</label>

<input
type="text"
name="semen_code"
class="form-control"
value="<?= htmlspecialchars($row['semen_code']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Technician

</label>

<input
type="text"
name="technician"
class="form-control"
value="<?= htmlspecialchars($row['technician']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Pregnancy Check Date

</label>

<input
type="date"
name="pregnancy_check_date"
class="form-control"
value="<?= $row['pregnancy_check_date']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Pregnancy Status

</label>

<select
name="pregnancy_status"
class="form-select">

<option <?= ($row['pregnancy_status']=="Pending") ? "selected" : ""; ?>>

Pending

</option>

<option <?= ($row['pregnancy_status']=="Confirmed") ? "selected" : ""; ?>>

Confirmed

</option>

<option <?= ($row['pregnancy_status']=="Failed") ? "selected" : ""; ?>>

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
class="form-control"
value="<?= $row['expected_calving_date']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Calving Status

</label>

<select
name="calving_status"
class="form-select">

<option <?= ($row['calving_status']=="Not Calved") ? "selected" : ""; ?>>

Not Calved

</option>

<option <?= ($row['calving_status']=="Calved") ? "selected" : ""; ?>>

Calved

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Actual Calving Date

</label>

<input
type="date"
name="actual_calving_date"
class="form-control"
value="<?= $row['actual_calving_date']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Number of Calves Born

</label>

<input
type="number"
name="calves_born"
class="form-control"
min="0"
value="<?= (int)$row['calves_born']; ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Calf Gender

</label>

<select
name="calf_gender"
class="form-select">

<option value="">Select</option>

<option value="Male" <?= ($row['calf_gender']=="Male") ? "selected" : ""; ?>>

Male

</option>

<option value="Female" <?= ($row['calf_gender']=="Female") ? "selected" : ""; ?>>

Female

</option>

<option value="Mixed" <?= ($row['calf_gender']=="Mixed") ? "selected" : ""; ?>>

Mixed

</option>

</select>

</div>

<div class="col-12 mb-4">

<label class="form-label">

Remarks

</label>

<textarea
name="remarks"
rows="4"
class="form-control"><?= htmlspecialchars($row['remarks']); ?></textarea>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a
href="view.php?id=<?= $id; ?>"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="bi bi-save"></i>

Update Record

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
| Average gestation period = 283 days
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function(){

    const breedingDate = document.getElementById("breeding_date");
    const calvingDate = document.getElementById("expected_calving_date");

    breedingDate.addEventListener("change", function(){

        if(this.value==="") return;

        let date = new Date(this.value);

        date.setDate(date.getDate()+283);

        let year = date.getFullYear();

        let month = String(date.getMonth()+1).padStart(2,"0");

        let day = String(date.getDate()).padStart(2,"0");

        calvingDate.value = `${year}-${month}-${day}`;

    });

});

</script>

</body>
</html>