<?php

$pageTitle = "Feed Management";
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
SELECT *
FROM feed_inventory
";

if(isset($_GET['search']) && !empty(trim($_GET['search'])))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql .= "
    WHERE

    feed_name LIKE '%$search%'

    OR feed_type LIKE '%$search%'

    OR supplier LIKE '%$search%'
    ";
}

$sql .= " ORDER BY created_at DESC";

$query = mysqli_query($conn,$sql);

/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

$totalFeeds = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM feed_inventory
"))['total'];

$totalStock = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(quantity),0) total
FROM feed_inventory
"))['total'];

$lowStock = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM feed_inventory
WHERE quantity <= reorder_level
AND quantity > 0
"))['total'];

$outOfStock = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM feed_inventory
WHERE quantity <= 0
"))['total'];

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-basket2-fill"></i>

Feed Management

</h2>

<p>

Manage feed inventory, monitor stock levels and suppliers.

</p>

</div>

<!-- SUCCESS / ERROR ALERTS -->

<?php if(isset($_GET['added'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item added successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item deleted successfully.

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

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    Feed item deleted successfully.

    <button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    Feed item updated successfully.

    <button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['added'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="bi bi-check-circle-fill"></i>

    Feed item added successfully.

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

<!-- KPI CARDS -->

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-green">

<h5>Total Feed Types</h5>

<h2><?= $totalFeeds ?></h2>

<i class="bi bi-box-seam-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-blue">

<h5>Total Stock</h5>

<h2><?= number_format($totalStock,2) ?></h2>

<i class="bi bi-boxes"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-orange">

<h5>Low Stock</h5>

<h2><?= $lowStock ?></h2>

<i class="bi bi-exclamation-triangle-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-red">

<h5>Out of Stock</h5>

<h2><?= $outOfStock ?></h2>

<i class="bi bi-x-octagon-fill"></i>

</div>

</div>

</div>

<!-- SEARCH & ACTIONS -->

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
placeholder="Search by feed name, type or supplier..."
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

Add Feed

</a>

</div>

</div>

</div>

</div>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-basket2-fill"></i>

Feed Management

</h2>

<p>

Manage feed inventory, monitor stock levels and suppliers.

</p>

</div>

<!-- SUCCESS / ERROR ALERTS -->

<?php if(isset($_GET['added'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item added successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item updated successfully.

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

Feed item deleted successfully.

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

<!-- KPI CARDS -->

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-green">

<h5>Total Feed Types</h5>

<h2><?= $totalFeeds ?></h2>

<i class="bi bi-box-seam-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-blue">

<h5>Total Stock</h5>

<h2><?= number_format($totalStock,2) ?></h2>

<i class="bi bi-boxes"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-orange">

<h5>Low Stock</h5>

<h2><?= $lowStock ?></h2>

<i class="bi bi-exclamation-triangle-fill"></i>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="stat-card bg-red">

<h5>Out of Stock</h5>

<h2><?= $outOfStock ?></h2>

<i class="bi bi-x-octagon-fill"></i>

</div>

</div>

</div>

<!-- SEARCH & ACTIONS -->

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
placeholder="Search by feed name, type or supplier..."
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

Add Feed

</a>

</div>

</div>

</div>

</div>
<!-- FEED INVENTORY TABLE -->

<div class="dashboard-panel">

    <div class="dashboard-panel-header d-flex justify-content-between align-items-center">

        <h5>

            <i class="bi bi-basket2-fill"></i>

            Feed Inventory

        </h5>

        <span class="badge bg-success">

            <?= $totalFeeds ?> Feed Types

        </span>

    </div>

    <div class="dashboard-panel-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-success">

                    <tr>

                        <th>#</th>
                        <th>Feed Name</th>
                        <th>Feed Type</th>
                        <th>Supplier</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total Value</th>
                        <th>Status</th>
                        <th width="170">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if(mysqli_num_rows($query) > 0):

                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)):

                        $totalValue = $row['quantity'] * $row['cost_per_unit'];

                ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>

                        <strong><?= htmlspecialchars($row['feed_name']); ?></strong>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['feed_type']); ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['supplier']); ?>

                    </td>

                    <td>

                        <?= number_format($row['quantity'],2); ?>

                        <?= htmlspecialchars($row['unit']); ?>

                    </td>

                    <td>

                        KSh <?= number_format($row['cost_per_unit'],2); ?>

                    </td>

                    <td>

                        <strong class="text-success">

                            KSh <?= number_format($totalValue,2); ?>

                        </strong>

                    </td>

                    <td>

                        <?php

                        if($row['quantity'] <= 0)
                        {
                            echo '<span class="badge bg-danger">
                            <i class="bi bi-x-circle-fill"></i>
                            Out of Stock
                            </span>';
                        }
                        elseif($row['quantity'] <= $row['reorder_level'])
                        {
                            echo '<span class="badge bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Low Stock
                            </span>';
                        }
                        else
                        {
                            echo '<span class="badge bg-success">
                            <i class="bi bi-check-circle-fill"></i>
                            Available
                            </span>';
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

                            <i class="bi bi-pencil-square"></i>

                        </a>

                        <a
                        href="delete.php?id=<?= $row['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this feed item?');">

                            <i class="bi bi-trash"></i>

                        </a>

                    </td>

                </tr>

                <?php

                    endwhile;

                else:

                ?>

                <tr>

                    <td colspan="9" class="text-center py-5">

                        <i class="bi bi-basket2 fs-1 text-success"></i>

                        <h5 class="mt-3">

                            No Feed Inventory Found

                        </h5>

                        <p class="text-muted">

                            Click <strong>Add Feed</strong> to create your first inventory record.

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