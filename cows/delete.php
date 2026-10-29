<?php

$pageTitle = "Delete Cow";
$activePage = "cows";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"
    SELECT *
    FROM cows
    WHERE id='$id'
");

if(mysqli_num_rows($result)==0){
    header("Location: manage.php");
    exit();
}

$cow = mysqli_fetch_assoc($result);

if(isset($_POST['delete'])){

    mysqli_query($conn,"
        INSERT INTO activities(activity)
        VALUES('Deleted cow {$cow['tag_number']}')
    ");

    mysqli_query($conn,"
        DELETE FROM cows
        WHERE id='$id'
    ");

    header("Location: manage.php?deleted=1");
    exit();

}

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-trash-fill"></i>

Delete Cow

</h2>

<p>

Confirm deletion of this cow record.

</p>

</div>

<div class="glass-card">

<div class="card-header-custom bg-danger">

<h4>

<i class="bi bi-exclamation-triangle-fill"></i>

Delete Confirmation

</h4>

</div>

<div class="card-body-custom">

<div class="alert alert-warning">

<h5 class="mb-3">

<i class="bi bi-exclamation-circle"></i>

Warning

</h5>

<p>

You are about to permanently remove this cow from the system.

</p>

<p class="mb-0">

This action cannot be undone.

</p>

</div>

<table class="table table-bordered">

<tr>

<th width="220">Tag Number</th>

<td><?= htmlspecialchars($cow['tag_number']) ?></td>

</tr>

<tr>

<th>Name</th>

<td><?= htmlspecialchars($cow['name']) ?></td>

</tr>

<tr>

<th>Breed</th>

<td><?= htmlspecialchars($cow['breed']) ?></td>

</tr>

<tr>

<th>Gender</th>

<td><?= htmlspecialchars($cow['gender']) ?></td>

</tr>

<tr>

<th>Status</th>

<td>

<span class="badge bg-success">

<?= htmlspecialchars($cow['status']) ?>

</span>

</td>

</tr>

</table>

<form method="POST">

<div class="d-flex justify-content-end gap-2 mt-4">

<a href="manage.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="delete"
class="btn btn-danger"
onclick="return confirm('Are you sure you want to permanently delete this cow?');">

<i class="bi bi-trash-fill"></i>

Delete Cow

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>