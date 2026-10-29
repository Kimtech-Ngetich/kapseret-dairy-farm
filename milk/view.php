<?php

$pageTitle = "View Milk Record";
$activePage = "milk";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("Location: records.php");
    exit();
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT
    milk_records.*,
    cows.tag_number,
    cows.name
FROM milk_records
INNER JOIN cows
ON cows.id = milk_records.cow_id
WHERE milk_records.id='$id'
LIMIT 1
");

if(mysqli_num_rows($query)==0)
{
    header("Location: records.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-droplet-fill"></i>

Milk Record Details

</h2>

<p>

View complete information for this production record.

</p>

</div>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-card-text"></i>

Production Information

</h5>

</div>

<div class="dashboard-panel-body">

<div class="row">

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Cow Tag Number

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['tag_number']) ?>

</div>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Cow Name

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['name']) ?>

</div>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Production Date

</label>

<div class="form-control bg-light">

<?= date("d M Y", strtotime($row['production_date'])) ?>

</div>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Session

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($row['session']) ?>

</div>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Quantity

</label>

<div class="form-control bg-light">

<?= number_format($row['quantity'],2) ?> Litres

</div>

</div>

<div class="col-lg-6 mb-4">

<label class="form-label fw-bold">

Recorded On

</label>

<div class="form-control bg-light">

<?= date("d M Y H:i", strtotime($row['created_at'])) ?>

</div>

</div>
</div>

<hr>

<div class="row">

    <div class="col-lg-4 mb-3">

        <div class="glass-card text-center">

            <div class="card-body-custom">

                <i class="bi bi-droplet-half display-4 text-primary"></i>

                <h6 class="mt-3">Milk Produced</h6>

                <h3 class="fw-bold text-primary">

                    <?= number_format($row['quantity'],2) ?> L

                </h3>

            </div>

        </div>

    </div>

    <div class="col-lg-4 mb-3">

        <div class="glass-card text-center">

            <div class="card-body-custom">

                <i class="bi bi-sunrise-fill display-4 text-warning"></i>

                <h6 class="mt-3">Session</h6>

                <h3 class="fw-bold">

                    <?= htmlspecialchars($row['session']) ?>

                </h3>

            </div>

        </div>

    </div>

    <div class="col-lg-4 mb-3">

        <div class="glass-card text-center">

            <div class="card-body-custom">

                <i class="bi bi-calendar-check-fill display-4 text-success"></i>

                <h6 class="mt-3">Production Date</h6>

                <h5 class="fw-bold">

                    <?= date("d M Y", strtotime($row['production_date'])) ?>

                </h5>

            </div>

        </div>

    </div>

</div>

<hr>

<div class="d-flex justify-content-between flex-wrap gap-2">

    <a href="records.php" class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Back to Records

    </a>

    <div>

        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">

            <i class="bi bi-pencil-square"></i>

            Edit Record

        </a>

        <a href="delete.php?id=<?= $row['id'] ?>"
           class="btn btn-danger"
           onclick="return confirm('Delete this milk record?');">

            <i class="bi bi-trash-fill"></i>

            Delete Record

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