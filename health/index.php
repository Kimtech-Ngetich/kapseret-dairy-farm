<?php

$pageTitle = "Health Records";
$activePage = "health";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$search = "";

$sql = "
SELECT
hr.*,
c.tag_number,
c.name AS cow_name
FROM health_records hr
INNER JOIN cows c
ON hr.cow_id = c.id
";

if(isset($_GET['search']) && !empty(trim($_GET['search'])))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql .= "
    WHERE
    c.tag_number LIKE '%$search%'
    OR c.name LIKE '%$search%'
    OR hr.disease LIKE '%$search%'
    OR hr.veterinarian LIKE '%$search%'
    ";
}

$sql .= " ORDER BY hr.record_date DESC";

$query = mysqli_query($conn,$sql);

?>


<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    Health record deleted successfully.

    <button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="bi bi-exclamation-triangle-fill"></i>

    Unable to delete the selected health record.

    <button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php

/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

$totalRecords = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) total
FROM health_records
"))['total'];

$totalCost = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT IFNULL(SUM(cost),0) total
FROM health_records
"))['total'];

$thisMonth = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) total
FROM health_records
WHERE MONTH(record_date)=MONTH(CURDATE())
AND YEAR(record_date)=YEAR(CURDATE())
"))['total'];

$totalCows = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT COUNT(*) total
FROM cows
WHERE status='Active'
"))['total'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-heart-pulse-fill"></i>

Health Records

</h2>

<p>

Monitor diseases, treatments and veterinary services.

</p>

</div>

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-green">

<h5>Total Records</h5>

<h2><?= $totalRecords ?></h2>

<i class="bi bi-clipboard2-pulse"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-blue">

<h5>Active Cows</h5>

<h2><?= $totalCows ?></h2>

<i class="bi bi-tags-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-orange">

<h5>This Month</h5>

<h2><?= $thisMonth ?></h2>

<i class="bi bi-calendar-check"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-red">

<h5>Total Cost</h5>

<h2>KES <?= number_format($totalCost,2) ?></h2>

<i class="bi bi-cash-stack"></i>

</div>

</div>

</div>
<!-- Search & Actions -->

<div class="dashboard-panel mb-4">

    <div class="dashboard-panel-body">

        <div class="row align-items-center">

            <div class="col-md-8">

                <form method="GET">

                    <div class="input-group">

                        <span class="input-group-text bg-success text-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by Cow Tag, Cow Name, Disease or Veterinarian..."
                            value="<?= htmlspecialchars($search) ?>">

                        <button class="btn btn-success">

                            Search

                        </button>

                    </div>

                </form>

            </div>

            <div class="col-md-4 text-end">

                <a href="add.php" class="btn btn-success">

                    <i class="bi bi-plus-circle"></i>

                    Add Health Record

                </a>

            </div>

        </div>

    </div>

</div>

<!-- Health Records Table -->

<div class="dashboard-panel">

    <div class="dashboard-panel-header">

        <h5>

            <i class="bi bi-heart-pulse-fill"></i>

            Health Records

        </h5>

        <span class="badge bg-success">

            <?= $totalRecords ?> Records

        </span>

    </div>

    <div class="dashboard-panel-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-success">

                    <tr>

                        <th>#</th>
                        <th>Cow</th>
                        <th>Disease</th>
                        <th>Veterinarian</th>
                        <th>Cost</th>
                        <th>Date</th>
                        <th width="170">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($query)>0):

                    $no=1;

                    while($row=mysqli_fetch_assoc($query)):

                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>

                        <strong><?= htmlspecialchars($row['tag_number']); ?></strong>

                        <br>

                        <small class="text-muted">

                            <?= htmlspecialchars($row['cow_name']); ?>

                        </small>

                    </td>

                    <td><?= htmlspecialchars($row['disease']); ?></td>

                    <td><?= htmlspecialchars($row['veterinarian']); ?></td>

                    <td>

                        <span class="badge bg-warning text-dark">

                            KES <?= number_format($row['cost'],2); ?>

                        </span>

                    </td>

                    <td>

                        <?= date("d M Y",strtotime($row['record_date'])); ?>

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

                            <i class="bi bi-pencil-square"></i>

                        </a>

                        <a
                        href="delete.php?id=<?= $row['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this health record?')">

                            <i class="bi bi-trash"></i>

                        </a>

                    </td>

                </tr>

                <?php

                    endwhile;

                else:

                ?>

                <tr>

                    <td colspan="7" class="text-center py-5">

                        <i class="bi bi-heart-pulse fs-1 text-success"></i>

                        <h5 class="mt-3">

                            No Health Records Found

                        </h5>

                        <p class="text-muted">

                            Click <strong>Add Health Record</strong> to register the first treatment.

                        </p>

                    </td>

                </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>