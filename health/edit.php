<?php

$pageTitle = "Edit Health Record";
$activePage = "health";

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

$cows = mysqli_query($conn,"
SELECT id,tag_number,name
FROM cows
WHERE status='Active'
ORDER BY tag_number ASC
");

$query = mysqli_query($conn,"
SELECT *
FROM health_records
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

$message="";

if(isset($_POST['update']))
{

$cow_id=(int)$_POST['cow_id'];

$record_date=$_POST['record_date'];

$disease=mysqli_real_escape_string($conn,$_POST['disease']);

$symptoms=mysqli_real_escape_string($conn,$_POST['symptoms']);

$treatment=mysqli_real_escape_string($conn,$_POST['treatment']);

$veterinarian=mysqli_real_escape_string($conn,$_POST['veterinarian']);

$medicine=mysqli_real_escape_string($conn,$_POST['medicine']);

$cost=(float)$_POST['cost'];

$remarks=mysqli_real_escape_string($conn,$_POST['remarks']);

$update=mysqli_query($conn,"
UPDATE health_records SET

cow_id='$cow_id',

record_date='$record_date',

disease='$disease',

symptoms='$symptoms',

treatment='$treatment',

veterinarian='$veterinarian',

medicine='$medicine',

cost='$cost',

remarks='$remarks'

WHERE id='$id'
");

if($update)
{

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Updated a health record')
");

$message='
<div class="alert alert-success alert-dismissible fade show">

Health record updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>';

$row=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM health_records
WHERE id='$id'
"));

}
else
{

$message='
<div class="alert alert-danger">

Unable to update record.

</div>';

}

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Health Record

</h2>

<p>

Update disease diagnosis, treatment and veterinary information.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-heart-pulse-fill"></i>

Update Record

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

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option
value="<?= $cow['id']; ?>"
<?= $row['cow_id']==$cow['id'] ? 'selected' : '' ?>>

<?= $cow['tag_number']; ?>

-

<?= $cow['name']; ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Record Date

</label>

<input
type="date"
name="record_date"
class="form-control"
value="<?= $row['record_date']; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Disease

</label>

<input
type="text"
name="disease"
class="form-control"
value="<?= htmlspecialchars($row['disease']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Medicine

</label>

<input
type="text"
name="medicine"
class="form-control"
value="<?= htmlspecialchars($row['medicine']); ?>">

</div>
<div class="col-md-12 mb-3">

<label class="form-label">

Symptoms

</label>

<textarea
name="symptoms"
class="form-control"
rows="3"><?= htmlspecialchars($row['symptoms']); ?></textarea>

</div>

<div class="col-md-12 mb-3">

<label class="form-label">

Treatment

</label>

<textarea
name="treatment"
class="form-control"
rows="3"><?= htmlspecialchars($row['treatment']); ?></textarea>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Veterinarian

</label>

<input
type="text"
name="veterinarian"
class="form-control"
value="<?= htmlspecialchars($row['veterinarian']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Treatment Cost (KES)

</label>

<input
type="number"
name="cost"
class="form-control"
step="0.01"
min="0"
value="<?= $row['cost']; ?>">

</div>

<div class="col-md-12 mb-4">

<label class="form-label">

Remarks

</label>

<textarea
name="remarks"
class="form-control"
rows="3"><?= htmlspecialchars($row['remarks']); ?></textarea>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

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

</body>
</html>