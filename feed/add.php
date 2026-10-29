<?php

$pageTitle = "Add Feed";
$activePage = "feed";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$error = "";
$success = "";

if(isset($_POST['save']))
{
    $feed_name = mysqli_real_escape_string($conn,$_POST['feed_name']);
    $feed_type = mysqli_real_escape_string($conn,$_POST['feed_type']);
    $supplier = mysqli_real_escape_string($conn,$_POST['supplier']);
    $quantity = (float)$_POST['quantity'];
    $unit = mysqli_real_escape_string($conn,$_POST['unit']);
    $cost_per_unit = (float)$_POST['cost_per_unit'];
    $purchase_date = $_POST['purchase_date'];
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;
    $reorder_level = (float)$_POST['reorder_level'];

    // Determine Stock Status
    if($quantity <= 0)
    {
        $status = "Out of Stock";
    }
    elseif($quantity <= $reorder_level)
    {
        $status = "Low Stock";
    }
    else
    {
        $status = "Available";
    }

    $expiry_value = $expiry_date ? "'$expiry_date'" : "NULL";

    $sql = "
    INSERT INTO feed_inventory
    (
        feed_name,
        feed_type,
        supplier,
        quantity,
        unit,
        cost_per_unit,
        purchase_date,
        expiry_date,
        reorder_level,
        status
    )
    VALUES
    (
        '$feed_name',
        '$feed_type',
        '$supplier',
        '$quantity',
        '$unit',
        '$cost_per_unit',
        '$purchase_date',
        $expiry_value,
        '$reorder_level',
        '$status'
    )";

    if(mysqli_query($conn,$sql))
    {
        header("Location:index.php?added=1");
        exit;
    }
    else
    {
        $error = "Unable to save feed information.";
    }
}
?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-basket2-fill"></i>

Add Feed Inventory

</h2>

<p>

Register a new feed item into the inventory.

</p>

</div>

<?php if($error): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

<?= $error; ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-pencil-square"></i>

Feed Information

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Feed Name <span class="text-danger">*</span>

</label>

<input
type="text"
name="feed_name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Feed Type <span class="text-danger">*</span>

</label>

<select
name="feed_type"
class="form-select"
required>

<option value="">Select Feed Type</option>

<option>Dairy Meal</option>

<option>Hay</option>

<option>Silage</option>

<option>Napier Grass</option>

<option>Maize Germ</option>

<option>Cotton Seed Cake</option>

<option>Mineral Supplements</option>

<option>Other</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Supplier

</label>

<input
type="text"
name="supplier"
class="form-control">

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Quantity <span class="text-danger">*</span>

</label>

<input
type="number"
step="0.01"
min="0"
name="quantity"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Unit <span class="text-danger">*</span>

</label>

<select
name="unit"
class="form-select"
required>

<option value="">Select</option>

<option>Kg</option>

<option>Bags</option>

<option>Bales</option>

<option>Litres</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Cost Per Unit (KSh)

</label>

<input
type="number"
step="0.01"
min="0"
name="cost_per_unit"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Purchase Date

</label>

<input
type="date"
name="purchase_date"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Expiry Date

</label>

<input
type="date"
name="expiry_date"
class="form-control">

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Reorder Level

</label>

<input
type="number"
step="0.01"
min="0"
name="reorder_level"
class="form-control"
value="50">

</div>

</div>

<div class="text-end">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

<button
type="submit"
name="save"
class="btn btn-success">

<i class="bi bi-save"></i>

Save Feed

</button>

</div>

</form>

</div>

</div>
</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

    const form = document.querySelector("form");

    const purchaseDate = document.querySelector("[name='purchase_date']");
    const expiryDate = document.querySelector("[name='expiry_date']");
    const quantity = document.querySelector("[name='quantity']");
    const cost = document.querySelector("[name='cost_per_unit']");
    const reorder = document.querySelector("[name='reorder_level']");

    form.addEventListener("submit", function(e){

        if(parseFloat(quantity.value) < 0)
        {
            alert("Quantity cannot be negative.");
            quantity.focus();
            e.preventDefault();
            return;
        }

        if(parseFloat(cost.value) < 0)
        {
            alert("Cost per unit cannot be negative.");
            cost.focus();
            e.preventDefault();
            return;
        }

        if(parseFloat(reorder.value) < 0)
        {
            alert("Reorder level cannot be negative.");
            reorder.focus();
            e.preventDefault();
            return;
        }

        if(expiryDate.value !== "" && purchaseDate.value !== "")
        {
            if(new Date(expiryDate.value) < new Date(purchaseDate.value))
            {
                alert("Expiry date cannot be earlier than the purchase date.");
                expiryDate.focus();
                e.preventDefault();
            }
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>