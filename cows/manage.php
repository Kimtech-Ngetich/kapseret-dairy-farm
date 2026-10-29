<?php

$pageTitle = "Cow Management";
$activePage = "cows";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search = "";

$where = "";

if (isset($_GET['search']) && trim($_GET['search']) != "") {

    $search = mysqli_real_escape_string($conn, trim($_GET['search']));

    $where = "
        WHERE tag_number LIKE '%$search%'
        OR name LIKE '%$search%'
        OR breed LIKE '%$search%'
    ";
}

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$totalCows = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM cows")
)['total'];

$activeCows = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total
        FROM cows
        WHERE status='Active'
")
)['total'];

$inactiveCows = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total
        FROM cows
        WHERE status='Inactive'
")
)['total'];

$femaleCows = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total
        FROM cows
        WHERE gender='Female'
")
)['total'];

/*
|--------------------------------------------------------------------------
| Cow Records
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn,"
    SELECT *
    FROM cows
    $where
    ORDER BY id DESC
");

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<main class="main-content">

<div class="container-fluid">

<div class="page-header">

    <div class="row align-items-center">

        <div class="col-md-8">

            <h2>
                <i class="bi bi-tags-fill"></i>
                Cow Management
            </h2>

            <p class="mb-0">
                Register, monitor and manage your dairy herd.
            </p>

        </div>

        <div class="col-md-4 text-md-end">

            <a href="add.php" class="btn btn-light">

                <i class="bi bi-plus-circle"></i>

                Register New Cow

            </a>

        </div>

    </div>

</div>

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="kpi-card kpi-green">

<h6>Total Cows</h6>

<h2><?= $totalCows ?></h2>

<i class="bi bi-tags-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="kpi-card kpi-blue">

<h6>Active</h6>

<h2><?= $activeCows ?></h2>

<i class="bi bi-check-circle-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="kpi-card kpi-orange">

<h6>Female Cows</h6>

<h2><?= $femaleCows ?></h2>

<i class="bi bi-gender-female"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="kpi-card kpi-red">

<h6>Inactive</h6>

<h2><?= $inactiveCows ?></h2>

<i class="bi bi-x-circle-fill"></i>

</div>

</div>

</div>

<!-- ==========================================================
     MANAGEMENT TOOLBAR
========================================================== -->

<div class="dashboard-panel">

    <div class="dashboard-panel-body">

        <div class="row align-items-center g-3">

            <!-- Search -->

            <div class="col-lg-5">

                <form method="GET">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search Tag Number, Name or Breed..."
                            value="<?= htmlspecialchars($search); ?>">

                        <button class="btn btn-success">

                            <i class="bi bi-search"></i>

                            Search

                        </button>

                    </div>

                </form>

            </div>

            <!-- Right Buttons -->

            <div class="col-lg-7 text-lg-end">

                <a href="manage.php" class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-clockwise"></i>

                    Refresh

                </a>

                <a href="add.php" class="btn btn-success">

                    <i class="bi bi-plus-circle"></i>

                    Add Cow

                </a>

                <button class="btn btn-primary">

                    <i class="bi bi-download"></i>

                    Export

                </button>

            </div>

        </div>

    </div>

</div>

<?php if(isset($_GET['success'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    <?= htmlspecialchars($_GET['success']) ?>

    <button
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

<?php endif; ?>

<?php if(isset($_GET['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="bi bi-exclamation-triangle-fill"></i>

    <?= htmlspecialchars($_GET['error']) ?>

    <button
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Cows | KDFMS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:linear-gradient(
        135deg,
        #f8fffc,
        #eefaf3,
        #e6f7ee
    );
    min-height:100vh;
}

.page-header{
    background:linear-gradient(
        135deg,
        #198754,
        #20c997
    );

    color:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:25px;
}

.card-box{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

.table{
    margin-top:15px;
}

</style>

</head>

<body>

<div class="container py-4">

    <div class="page-header">

        <h3>
            <i class="bi bi-tags-fill"></i>
            Cow Management
        </h3>

        <p class="mb-0">
            Total Registered Cows:
            <strong><?= $totalCows ?></strong>
        </p>

    </div>

    <div class="dashboard-panel">

<div class="dashboard-panel-header">

    <h5>

        <i class="bi bi-table"></i>

        Registered Cows

    </h5>

    <span class="badge bg-success">

        <?= $totalCows ?>

        Records

    </span>

</div>

<div class="dashboard-panel-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

               <thead>

<tr>

    <th width="60">#</th>

    <th>Cow</th>

    <th>Breed</th>

    <th>Gender</th>

    <th>Date of Birth</th>

    <th>Status</th>

    <th class="text-center" width="190">

        Actions

    </th>

</tr>

</thead>

               <tbody>

<?php

if(mysqli_num_rows($query)>0):

$no=1;

while($row=mysqli_fetch_assoc($query)):

$status=$row['status'];

$badge="bg-secondary";

if($status=="Active"){

    $badge="bg-success";

}
elseif($status=="Inactive"){

    $badge="bg-danger";

}
elseif($status=="Sold"){

    $badge="bg-dark";

}
elseif($status=="Pregnant"){

    $badge="bg-primary";

}
elseif($status=="Under Treatment"){

    $badge="bg-warning text-dark";

}

?>

<tr>

<td>

<?= $no++; ?>

</td>

<td>

<div class="d-flex align-items-center">

<div class="me-3">

<div class="user-avatar">

<i class="bi bi-tag-fill"></i>

</div>

</div>

<div>

<strong>

<?= htmlspecialchars($row['tag_number']) ?>

</strong>

<br>

<small class="text-muted">

<?= htmlspecialchars($row['name']) ?>

</small>

</div>

</div>

</td>

<td>

<?= htmlspecialchars($row['breed']) ?>

</td>

<td>

<?= htmlspecialchars($row['gender']) ?>

</td>

<td>

<?= date("d M Y",strtotime($row['date_of_birth'])) ?>

</td>

<td>

<span class="badge <?= $badge ?>">

<?= htmlspecialchars($status) ?>

</span>

</td>

<td class="text-center">

<a
href="view.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-outline-primary"
title="View">

<i class="bi bi-eye"></i>

</a>

<a
href="edit.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-outline-warning"
title="Edit">

<i class="bi bi-pencil"></i>

</a>

<a
href="delete.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-outline-danger"
title="Delete"

onclick="return confirm('Delete this cow permanently?');">

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

<i class="bi bi-inbox display-3 text-muted"></i>

<h5 class="mt-3">

No Cow Records Found

</h5>

<p class="text-muted">

Click "Register New Cow" to add your first cow.

</p>

<a href="add.php" class="btn btn-success">

<i class="bi bi-plus-circle"></i>

Register First Cow

</a>

</td>

</tr>

<?php endif; ?>

</tbody>

            </table>

        </div>

    </div>

</div>
</div>

</main>

<?php include "../includes/footer.php"; ?>

</body>
</html>