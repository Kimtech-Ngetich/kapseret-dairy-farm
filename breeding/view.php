<?php

$pageTitle = "Breeding Record Details";
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

$query = mysqli_query($conn,"
SELECT
    br.*,
    c.tag_number,
    c.name,
    c.breed
FROM breeding_records br
INNER JOIN cows c
ON br.cow_id = c.id
WHERE br.id='$id'
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

<i class="bi bi-eye-fill"></i>

Breeding Record Details

</h2>

<p>

Complete breeding and pregnancy information.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-heart-pulse-fill"></i>

Breeding Information

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-md-6 mb-4">

<strong>Tag Number</strong><br>

<?= htmlspecialchars($row['tag_number']); ?>

</div>

<div class="col-md-6 mb-4">

<strong>Cow Name</strong><br>

<?= htmlspecialchars($row['name']); ?>

</div>

<div class="col-md-6 mb-4">

<strong>Breed</strong><br>

<?= htmlspecialchars($row['breed']); ?>

</div>

<div class="col-md-6 mb-4">

<strong>Breeding Date</strong><br>

<?= date("d M Y",strtotime($row['breeding_date'])); ?>

</div>

<div class="col-md-6 mb-4">

<strong>Breeding Method</strong><br>

<?= htmlspecialchars($row['breeding_method']); ?>

</div>

<div class="col-md-6 mb-4">

<strong>Bull Name</strong><br>

<?= !empty($row['bull_name']) ? htmlspecialchars($row['bull_name']) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Semen Code</strong><br>

<?= !empty($row['semen_code']) ? htmlspecialchars($row['semen_code']) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Technician</strong><br>

<?= !empty($row['technician']) ? htmlspecialchars($row['technician']) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Pregnancy Check Date</strong><br>

<?= !empty($row['pregnancy_check_date']) ? date("d M Y",strtotime($row['pregnancy_check_date'])) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Pregnancy Status</strong><br>

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

</div>
<div class="col-md-6 mb-4">

<strong>Expected Calving Date</strong><br>

<?= !empty($row['expected_calving_date']) ? date("d M Y",strtotime($row['expected_calving_date'])) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Calving Status</strong><br>

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

</div>

<div class="col-md-6 mb-4">

<strong>Actual Calving Date</strong><br>

<?= !empty($row['actual_calving_date']) ? date("d M Y",strtotime($row['actual_calving_date'])) : "-"; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Calves Born</strong><br>

<?= (int)$row['calves_born']; ?>

</div>

<div class="col-md-6 mb-4">

<strong>Calf Gender</strong><br>

<?= !empty($row['calf_gender']) ? htmlspecialchars($row['calf_gender']) : "-"; ?>

</div>

<div class="col-12 mb-4">

<strong>Remarks</strong>

<div class="border rounded p-3 bg-light mt-2">

<?= !empty($row['remarks']) ? nl2br(htmlspecialchars($row['remarks'])) : "No remarks available."; ?>

</div>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning">

<i class="bi bi-pencil"></i>

Edit Record

</a>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>