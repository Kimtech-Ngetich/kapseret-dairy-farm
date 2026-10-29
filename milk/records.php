<?php

$pageTitle = "Milk Production";
$activePage = "milk";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| SEARCH & FILTERS
|--------------------------------------------------------------------------
*/

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : "";
$filter_date = $_GET['date'] ?? "";
$filter_session = $_GET['session'] ?? "";

/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

$todayMilkQuery = mysqli_query($conn,"
SELECT SUM(quantity) AS total
FROM milk_records
WHERE production_date = CURDATE()
");

$todayMilk = mysqli_fetch_assoc($todayMilkQuery)['total'] ?? 0;

$recordQuery = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM milk_records
");

$totalRecords = mysqli_fetch_assoc($recordQuery)['total'] ?? 0;

$morningQuery = mysqli_query($conn,"
SELECT SUM(quantity) AS total
FROM milk_records
WHERE production_date=CURDATE()
AND session='Morning'
");

$morningMilk = mysqli_fetch_assoc($morningQuery)['total'] ?? 0;

$eveningQuery = mysqli_query($conn,"
SELECT SUM(quantity) AS total
FROM milk_records
WHERE production_date=CURDATE()
AND session='Evening'
");

$eveningMilk = mysqli_fetch_assoc($eveningQuery)['total'] ?? 0;

/*
|--------------------------------------------------------------------------
| FETCH RECORDS
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
milk_records.*,
cows.tag_number,
cows.name

FROM milk_records

INNER JOIN cows
ON cows.id = milk_records.cow_id

WHERE 1
";

if($search!=""){
    $sql .= " AND (
        cows.tag_number LIKE '%$search%'
        OR cows.name LIKE '%$search%'
    )";
}

if($filter_date!=""){
    $sql .= " AND production_date='$filter_date'";
}

if($filter_session!=""){
    $sql .= " AND session='$filter_session'";
}

$sql .= "
ORDER BY production_date DESC,
created_at DESC
";

$query = mysqli_query($conn,$sql);

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";
?>

<div class="main-content">

<div class="container-fluid">

<!-- PAGE HEADER -->

<div class="page-header">

<h2>

<i class="bi bi-droplet-fill"></i>

Milk Production

</h2>

<p>

Monitor and manage daily milk production records.

</p>

</div>

<!-- KPI CARDS -->

<div class="row mb-4">

<div class="col-lg-3 col-md-6 mb-3">

<div class="kpi-card kpi-green">

<h6>Today's Production</h6>

<h2><?= number_format($todayMilk,2) ?> L</h2>

<i class="bi bi-droplet-half"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="kpi-card kpi-blue">

<h6>Total Records</h6>

<h2><?= $totalRecords ?></h2>

<i class="bi bi-table"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="kpi-card kpi-orange">

<h6>Morning Milk</h6>

<h2><?= number_format($morningMilk,2) ?> L</h2>

<i class="bi bi-sunrise-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-3">

<div class="kpi-card kpi-purple">

<h6>Evening Milk</h6>

<h2><?= number_format($eveningMilk,2) ?> L</h2>

<i class="bi bi-moon-stars-fill"></i>

</div>

</div>

</div>

<!-- RECORDS PANEL -->

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-droplet"></i>

Milk Production Records

</h5>

<a href="add.php" class="btn btn-success">

<i class="bi bi-plus-circle"></i>

Record Milk

</a>

</div>

<div class="dashboard-panel-body">

<!-- FILTERS -->

<form method="GET" class="row g-3 mb-4">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search cow name or tag..."
value="<?= htmlspecialchars($search) ?>">

</div>

<div class="col-md-3">

<input
type="date"
name="date"
class="form-control"
value="<?= htmlspecialchars($filter_date) ?>">

</div>

<div class="col-md-3">

<select
name="session"
class="form-select">

<option value="">All Sessions</option>

<option value="Morning"
<?=($filter_session=="Morning")?"selected":"";?>>

Morning

</option>

<option value="Evening"
<?=($filter_session=="Evening")?"selected":"";?>>

Evening

</option>

</select>

</div>

<div class="col-md-2 d-grid">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</form>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>#</th>

<th>Date</th>

<th>Tag</th>

<th>Cow Name</th>

<th>Session</th>

<th>Quantity (L)</th>

<th>Recorded</th>

<th width="180">

Actions

</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0){

$no=1;

while($row=mysqli_fetch_assoc($query)){

?>
?>

<tr>

    <td><?= $no++ ?></td>

    <td>

        <?= date("d M Y", strtotime($row['production_date'])) ?>

    </td>

    <td>

        <span class="badge bg-success">

            <?= htmlspecialchars($row['tag_number']) ?>

        </span>

    </td>

    <td>

        <strong>

            <?= htmlspecialchars($row['name']) ?>

        </strong>

    </td>

    <td>

        <?php
        if($row['session']=="Morning"){
            echo "<span class='badge bg-warning text-dark'>
                    <i class='bi bi-sunrise-fill'></i>
                    Morning
                  </span>";
        }else{
            echo "<span class='badge bg-primary'>
                    <i class='bi bi-moon-stars-fill'></i>
                    Evening
                  </span>";
        }
        ?>

    </td>

    <td>

        <strong class="text-success">

            <?= number_format($row['quantity'],2) ?>

            L

        </strong>

    </td>

    <td>

        <?= date("d M Y H:i",strtotime($row['created_at'])) ?>

    </td>

    <td>

        <div class="btn-group">

            <a
            href="view.php?id=<?= $row['id'] ?>"
            class="btn btn-info btn-sm">

                <i class="bi bi-eye-fill"></i>

            </a>

            <a
            href="edit.php?id=<?= $row['id'] ?>"
            class="btn btn-warning btn-sm">

                <i class="bi bi-pencil-fill"></i>

            </a>

            <a
            href="delete.php?id=<?= $row['id'] ?>"
            class="btn btn-danger btn-sm"
            onclick="return confirm('Delete this milk record?')">

                <i class="bi bi-trash-fill"></i>

            </a>

        </div>

    </td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-droplet display-4 text-muted"></i>

<h5 class="mt-3">

No milk production records found.

</h5>

<p class="text-muted">

Click the <strong>Record Milk</strong> button to add the first production record.

</p>

<a href="add.php" class="btn btn-success">

<i class="bi bi-plus-circle"></i>

Record Milk

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>

<script>

document.addEventListener("DOMContentLoaded",function(){

const search=document.querySelector("input[name='search']");

if(search){

search.focus();

}

});

</script>

</body>

</html>