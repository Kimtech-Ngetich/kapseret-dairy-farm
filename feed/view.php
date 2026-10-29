<?php

$pageTitle = "View Feed";
$activePage = "feed";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM feed_inventory WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=1");
    exit;
}

$feed = $result->fetch_assoc();

$totalValue = $feed['quantity'] * $feed['cost_per_unit'];

if ($feed['quantity'] <= 0) {
    $status = "Out of Stock";
    $badge = "danger";
} elseif ($feed['quantity'] <= $feed['reorder_level']) {
    $status = "Low Stock";
    $badge = "warning";
} else {
    $status = "Available";
    $badge = "success";
}

$stockPercent = 100;

if ($feed['reorder_level'] > 0) {
    $stockPercent = min(100, ($feed['quantity'] / ($feed['reorder_level'] * 2)) * 100);
}
?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header mb-4">
    <h2><i class="bi bi-basket2-fill text-success"></i> Feed Details</h2>
    <p class="text-muted">
        Detailed information about the selected feed inventory item.
    </p>
</div>

<div class="row mb-4">

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">

                <i class="bi bi-box-seam display-3 text-success"></i>

                <h3 class="mt-3">
                    <?= htmlspecialchars($feed['feed_name']); ?>
                </h3>

                <span class="badge bg-<?= $badge ?>">
                    <?= $status ?>
                </span>

            </div>
        </div>
    </div>

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">
                    <i class="bi bi-info-circle-fill"></i>
                    Inventory Summary
                </h5>

            </div>

            <div class="card-body">

                <div class="row text-center">

                    <div class="col-md-4">
                        <h6>Quantity</h6>
                        <h4>
                            <?= number_format($feed['quantity'],2); ?>
                            <?= htmlspecialchars($feed['unit']); ?>
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <h6>Cost / Unit</h6>
                        <h4>
                            KSh <?= number_format($feed['cost_per_unit'],2); ?>
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <h6>Total Value</h6>
                        <h4 class="text-success">
                            KSh <?= number_format($totalValue,2); ?>
                        </h4>
                    </div>

                </div>

                <hr>

                <label class="fw-bold">
                    Stock Level
                </label>

                <div class="progress mt-2" style="height:25px;">

                    <div class="progress-bar bg-success"
                         role="progressbar"
                         style="width: <?= $stockPercent ?>%;">

                        <?= round($stockPercent) ?>%

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm border-0">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-list-ul"></i>

Feed Information

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-4">
<strong>Feed Type</strong>
<p><?= htmlspecialchars($feed['feed_type']); ?></p>
</div>

<div class="col-md-6 mb-4">
<strong>Supplier</strong>
<p><?= htmlspecialchars($feed['supplier']); ?></p>
</div>

<div class="col-md-6 mb-4">
<strong>Purchase Date</strong>
<p><?= date("d M Y", strtotime($feed['purchase_date'])); ?></p>
</div>

<div class="col-md-6 mb-4">
<strong>Expiry Date</strong>
<p>
<?= !empty($feed['expiry_date']) ?
date("d M Y", strtotime($feed['expiry_date'])) :
"Not Set"; ?>
</p>
</div>

<div class="col-md-6 mb-4">
<strong>Reorder Level</strong>
<p>
<?= number_format($feed['reorder_level'],2); ?>
<?= htmlspecialchars($feed['unit']); ?>
</p>
</div>

<div class="col-md-6 mb-4">
<strong>Status</strong>
<p>
<span class="badge bg-<?= $badge ?>">
<?= $status ?>
</span>
</p>
</div>

<div class="col-md-12 mb-4">
<strong>Date Added</strong>
<p>
<?= date("d M Y H:i", strtotime($feed['created_at'])); ?>
</p>
</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a href="index.php" class="btn btn-secondary">
<i class="bi bi-arrow-left"></i>
Back
</a>

<a href="edit.php?id=<?= $feed['id']; ?>"
class="btn btn-warning">

<i class="bi bi-pencil-square"></i>
Edit Feed

</a>

</div>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>