<?php

$pageTitle = "View Health Record";
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

$query = mysqli_query($conn,"
SELECT
hr.*,
c.tag_number,
c.name AS cow_name,
c.breed,
c.gender
FROM health_records hr
INNER JOIN cows c
ON hr.cow_id=c.id
WHERE hr.id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-pulse-fill"></i>

Health Record Details

</h2>

<p>

View complete medical information for this treatment record.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-clipboard2-pulse"></i>

Medical Record

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Cow Tag</label>

<p><?= htmlspecialchars($row['tag_number']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Cow Name</label>

<p><?= htmlspecialchars($row['cow_name']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Breed</label>

<p><?= htmlspecialchars($row['breed']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Gender</label>

<p><?= htmlspecialchars($row['gender']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Disease</label>

<p><?= htmlspecialchars($row['disease']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Medicine</label>

<p><?= htmlspecialchars($row['medicine']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Veterinarian</label>

<p><?= htmlspecialchars($row['veterinarian']); ?></p>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">Treatment Cost</label>

<p>KES <?= number_format($row['cost'],2); ?></p>

</div>

<div class="col-md-12 mb-4">

<label class="fw-bold text-success">Symptoms</label>

<div class="border rounded p-3 bg-light">

<?= nl2br(htmlspecialchars($row['symptoms'])); ?>

</div>

</div>
<div class="col-md-12 mb-4">

<label class="fw-bold text-success">Treatment</label>

<div class="border rounded p-3 bg-light">

<?= nl2br(htmlspecialchars($row['treatment'])); ?>

</div>

</div>

<div class="col-md-12 mb-4">

<label class="fw-bold text-success">Remarks</label>

<div class="border rounded p-3 bg-light">

<?= !empty($row['remarks']) ? nl2br(htmlspecialchars($row['remarks'])) : '<span class="text-muted">No remarks provided.</span>'; ?>

</div>

</div>

<div class="col-md-6">

<label class="fw-bold text-success">Record Date</label>

<p><?= date("d M Y",strtotime($row['record_date'])); ?></p>

</div>

<div class="col-md-6">

<label class="fw-bold text-success">Created On</label>

<p><?= date("d M Y H:i",strtotime($row['created_at'])); ?></p>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<div>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Delete this health record?');">

<i class="bi bi-trash"></i>

Delete

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