<?php

$pageTitle = "Breeding Management";
$activePage = "breeding";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $query = mysqli_query($conn,"
    SELECT
        br.*,
        c.tag_number,
        c.name
    FROM breeding_records br
    INNER JOIN cows c
        ON br.cow_id=c.id
    WHERE
        c.tag_number LIKE '%$search%'
        OR c.name LIKE '%$search%'
        OR br.pregnancy_status LIKE '%$search%'
    ORDER BY br.id DESC
    ");
}
else
{
    $query = mysqli_query($conn,"
    SELECT
        br.*,
        c.tag_number,
        c.name
    FROM breeding_records br
    INNER JOIN cows c
        ON br.cow_id=c.id
    ORDER BY br.id DESC
    ");
}

/*
|--------------------------------------------------------------------------
| DASHBOARD COUNTERS
|--------------------------------------------------------------------------
*/

$totalRecords = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM breeding_records
"))['total'];

$pregnant = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM breeding_records
WHERE pregnancy_status='Confirmed'
AND calving_status='Not Calved'
"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM breeding_records
WHERE pregnancy_status='Pending'
"))['total'];

$calved = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM breeding_records
WHERE calving_status='Calved'
"))['total'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-gender-female"></i>

Breeding Management

</h2>

<p>

Manage breeding, pregnancy and calving records.

</p>

</div>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Breeding record deleted successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<div class="row mb-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-card bg-green">

<i class="bi bi-journal-medical"></i>

<h5>Total Records</h5>

<h2><?= $totalRecords ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-card bg-blue">

<i class="bi bi-heart-fill"></i>

<h5>Pregnant Cows</h5>

<h2><?= $pregnant ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-card bg-orange">

<i class="bi bi-hourglass-split"></i>

<h5>Pending Checks</h5>

<h2><?= $pending ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-card bg-red">

<i class="bi bi-emoji-smile"></i>

<h5>Calved</h5>

<h2><?= $calved ?></h2>

</div>

</div>

</div>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Breeding record deleted successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Breeding record updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

Unable to complete the requested operation.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>


<div class="dashboard-panel">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-table"></i>

Breeding Records

</h5>

<a href="add.php" class="btn btn-success">

<i class="bi bi-plus-circle"></i>

New Breeding Record

</a>

</div>

<div class="dashboard-panel-body">

<form method="GET" class="row mb-4">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search by Tag Number, Cow Name or Pregnancy Status..."
value="<?= htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-success w-100">

<i class="bi bi-search"></i>

Search

</button>

</div>

</form>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-success">

<tr>

<th>#</th>

<th>Tag Number</th>

<th>Cow Name</th>

<th>Breeding Date</th>

<th>Method</th>

<th>Pregnancy</th>

<th>Expected Calving</th>

<th>Calving</th>

<th width="170">Actions</th>

</tr>

</thead>

<tbody>

<?php

$i = 1;

if(mysqli_num_rows($query)>0):

while($row=mysqli_fetch_assoc($query)):

?>

<tr>

<td><?= $i++; ?></td>

<td>

<strong><?= htmlspecialchars($row['tag_number']); ?></strong>

</td>

<td><?= htmlspecialchars($row['name']); ?></td>

<td>

<?= date("d M Y",strtotime($row['breeding_date'])); ?>

</td>

<td>

<?= htmlspecialchars($row['breeding_method']); ?>

</td>

<td>

<?php

switch($row['pregnancy_status'])
{

case "Confirmed":

echo "<span class='badge bg-success'>Confirmed</span>";

break;

case "Pending":

echo "<span class='badge bg-warning text-dark'>Pending</span>";

break;

default:

echo "<span class='badge bg-danger'>Failed</span>";

}

?>

</td>

<td>

<?php

if(!empty($row['expected_calving_date']))
{

echo date("d M Y",strtotime($row['expected_calving_date']));

}
else{

echo "-";

}

?>

</td>

<td>

<?php

if($row['calving_status']=="Calved")
{

echo "<span class='badge bg-primary'>Calved</span>";

}
else
{

echo "<span class='badge bg-secondary'>Not Calved</span>";

}

?>

</td>

<td>

<a
href="view.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-info">

<i class="bi bi-eye"></i>

</a>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-warning">

<i class="bi bi-pencil"></i>

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this breeding record?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="9" class="text-center text-muted py-5">

<i class="bi bi-inbox fs-1"></i>

<br><br>

No breeding records found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>

</html>