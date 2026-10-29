<?php

$pageTitle = "Feed Usage";
$activePage = "feed";

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

fu.*,

c.tag_number,

c.name AS cow_name,

fi.feed_name,

fi.unit

FROM feed_usage fu

INNER JOIN cows c
ON fu.cow_id=c.id

INNER JOIN feed_inventory fi
ON fu.feed_id=fi.id

";

if(isset($_GET['search']) && !empty(trim($_GET['search'])))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql .= "

    WHERE

    c.tag_number LIKE '%$search%'

    OR

    c.name LIKE '%$search%'

    OR

    fi.feed_name LIKE '%$search%'

    ";
}

$sql .= " ORDER BY feeding_date DESC";

$query = mysqli_query($conn,$sql);

/*
|--------------------------------------------------------------------------
| STATISTICS
|--------------------------------------------------------------------------
*/

$totalRecords = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM feed_usage
"))['total'];

$totalFeedUsed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(quantity),0) total
FROM feed_usage
"))['total'];

$todayFeed = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(quantity),0) total
FROM feed_usage
WHERE feeding_date=CURDATE()
"))['total'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-basket-fill"></i>

Feed Usage

</h2>

<p>

Monitor daily feed consumption and inventory utilization.

</p>

</div>

<?php if(isset($_GET['added'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed usage recorded successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed usage updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed usage deleted successfully.

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

<div class="row g-4 mb-4">

<div class="col-lg-4">

<div class="stat-card bg-green">

<h5>Total Records</h5>

<h2><?= $totalRecords ?></h2>

<i class="bi bi-list-check"></i>

</div>

</div>

<div class="col-lg-4">

<div class="stat-card bg-blue">

<h5>Total Feed Used</h5>

<h2><?= number_format($totalFeedUsed,2) ?></h2>

<i class="bi bi-basket-fill"></i>

</div>

</div>

<div class="col-lg-4">

<div class="stat-card bg-orange">

<h5>Today's Feed</h5>

<h2><?= number_format($todayFeed,2) ?></h2>

<i class="bi bi-calendar-check"></i>

</div>

</div>

</div>

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
placeholder="Search by Cow Tag, Cow Name or Feed..."
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

Record Feed Usage

</a>

</div>

</div>

</div>

</div>
<!-- FEED USAGE TABLE -->

<div class="dashboard-panel">

<div class="dashboard-panel-header d-flex justify-content-between align-items-center">

<h5>

<i class="bi bi-basket-fill"></i>

Feed Usage Records

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

<th>Feed</th>

<th>Quantity</th>

<th>Time</th>

<th>Date</th>

<th>Remarks</th>

<th width="170">Actions</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query) > 0):

$no = 1;

while($row = mysqli_fetch_assoc($query)):

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

<td>

<?= htmlspecialchars($row['feed_name']); ?>

</td>

<td>

<span class="badge bg-primary">

<?= number_format($row['quantity'],2); ?>

<?= htmlspecialchars($row['unit']); ?>

</span>

</td>

<td>

<?= htmlspecialchars($row['feeding_time']); ?>

</td>

<td>

<?= date("d M Y",strtotime($row['feeding_date'])); ?>

</td>

<td>

<?= !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : '<span class="text-muted">None</span>'; ?>

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
onclick="return confirm('Delete this feed usage record?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php

endwhile;

else:

?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-basket fs-1 text-success"></i>

<h5 class="mt-3">

No Feed Usage Records Found

</h5>

<p class="text-muted">

Click <strong>Record Feed Usage</strong> to add the first feeding record.

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