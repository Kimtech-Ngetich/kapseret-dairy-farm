<?php

$pageTitle = "View Cow";
$activePage = "cows";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if(!isset($_GET['id']))
{
    header("Location: manage.php");
    exit();
}

$id = intval($_GET['id']);

$query = mysqli_query($conn,"
SELECT *
FROM cows
WHERE id='$id'
LIMIT 1
");

if(mysqli_num_rows($query)==0)
{
    header("Location: manage.php");
    exit();
}

$cow = mysqli_fetch_assoc($query);

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

?>

<div class="main-content">

<div class="page-header">

<h2>
<i class="bi bi-eye-fill"></i>
Cow Profile
</h2>

<p>
Complete information about this dairy cow.
</p>

</div>

<div class="glass-card">

<div class="card-body-custom">

<div class="row">

<div class="col-lg-4 text-center">

<div class="mb-4">

<div class="user-avatar mx-auto"
style="width:120px;height:120px;font-size:50px;">

<i class="bi bi-tags-fill"></i>

</div>

<h3 class="mt-3">

<?= htmlspecialchars($cow['name']) ?>

</h3>

<p class="text-muted">

<?= htmlspecialchars($cow['tag_number']) ?>

</p>

<?php

$status = strtolower($cow['status']);

$badge = "bg-success";

if($status=="sold")
{
    $badge="bg-warning text-dark";
}
elseif($status=="dead")
{
    $badge="bg-danger";
}

?>

<span class="badge <?= $badge ?> fs-6 px-3 py-2">

<?= htmlspecialchars($cow['status']) ?>

</span>

</div>

</div>

<div class="col-lg-8">

<div class="row">

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Tag Number

</label>

<div class="form-control">

<?= htmlspecialchars($cow['tag_number']) ?>

</div>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Cow Name

</label>

<div class="form-control">

<?= htmlspecialchars($cow['name']) ?>

</div>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Breed

</label>

<div class="form-control">

<?= htmlspecialchars($cow['breed']) ?>

</div>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Gender

</label>

<div class="form-control">

<?= htmlspecialchars($cow['gender']) ?>

</div>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Date of Birth

</label>

<div class="form-control">

<?= htmlspecialchars($cow['date_of_birth']) ?>

</div>

</div>

<div class="col-md-6 mb-4">

<label class="fw-bold text-success">

Status

</label>

<div class="form-control">

<?= htmlspecialchars($cow['status']) ?>

</div>

</div>

<div class="col-md-12">

<label class="fw-bold text-success">

Registered On

</label>

<div class="form-control">

<?= date("d M Y",strtotime($cow['created_at'])) ?>

</div>

</div>

</div>

</div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

<a href="manage.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<a href="edit.php?id=<?= $cow['id'] ?>"
class="btn btn-primary">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a href="delete.php?id=<?= $cow['id'] ?>"
class="btn btn-danger"
onclick="return confirm('Delete this cow?')">

<i class="bi bi-trash"></i>

Delete

</a>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>