<?php

$pageTitle = "Record Feed Usage";
$activePage = "feed";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$error = "";

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id, tag_number, name
FROM cows
WHERE status='Active'
ORDER BY tag_number ASC
");

/*
|--------------------------------------------------------------------------
| LOAD AVAILABLE FEED
|--------------------------------------------------------------------------
*/

$feeds = mysqli_query($conn,"
SELECT *
FROM feed_inventory
WHERE quantity > 0
ORDER BY feed_name ASC
");

/*
|--------------------------------------------------------------------------
| SAVE FEED USAGE
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{
    $cow_id = (int)$_POST['cow_id'];
    $feed_id = (int)$_POST['feed_id'];
    $quantity = (float)$_POST['quantity'];
    $feeding_time = mysqli_real_escape_string($conn,$_POST['feeding_time']);
    $feeding_date = $_POST['feeding_date'];
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

    /*
    |--------------------------------------------------------------------------
    | CHECK AVAILABLE STOCK
    |--------------------------------------------------------------------------
    */

    $feedQuery = mysqli_query($conn,"
    SELECT *
    FROM feed_inventory
    WHERE id='$feed_id'
    ");

    $feed = mysqli_fetch_assoc($feedQuery);

    if(!$feed)
    {
        $error = "Selected feed does not exist.";
    }
    elseif($quantity <= 0)
    {
        $error = "Quantity must be greater than zero.";
    }
    elseif($quantity > $feed['quantity'])
    {
        $error = "Insufficient stock. Available quantity is "
               . number_format($feed['quantity'],2)
               . " "
               . $feed['unit'];
    }
    else
    {
        mysqli_begin_transaction($conn);

        try
        {
            /*
            |--------------------------------------------------------------------------
            | SAVE FEED USAGE
            |--------------------------------------------------------------------------
            */

            mysqli_query($conn,"
            INSERT INTO feed_usage
            (
                cow_id,
                feed_id,
                quantity,
                feeding_time,
                feeding_date,
                remarks
            )
            VALUES
            (
                '$cow_id',
                '$feed_id',
                '$quantity',
                '$feeding_time',
                '$feeding_date',
                '$remarks'
            )
            ");

            /*
            |--------------------------------------------------------------------------
            | UPDATE INVENTORY
            |--------------------------------------------------------------------------
            */

            $newQuantity = $feed['quantity'] - $quantity;

            if($newQuantity <= 0)
            {
                $status = "Out of Stock";
            }
            elseif($newQuantity <= $feed['reorder_level'])
            {
                $status = "Low Stock";
            }
            else
            {
                $status = "Available";
            }

            mysqli_query($conn,"
            UPDATE feed_inventory
            SET
                quantity='$newQuantity',
                status='$status'
            WHERE id='$feed_id'
            ");

            mysqli_commit($conn);

            header("Location:index.php?added=1");
            exit;
        }
        catch(Exception $e)
        {
            mysqli_rollback($conn);
            $error = "Unable to record feed usage.";
        }
    }
}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-basket-fill"></i>

Record Feed Usage

</h2>

<p>

Record daily feed issued to cows. Feed inventory will be updated automatically.

</p>

</div>

<?php if(!empty($error)): ?>

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

Feed Usage Details

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<!-- Cow -->

<div class="col-md-6 mb-3">

<label class="form-label">

Select Cow <span class="text-danger">*</span>

</label>

<select
name="cow_id"
class="form-select"
required>

<option value="">Choose Cow</option>

<?php while($cow = mysqli_fetch_assoc($cows)): ?>

<option value="<?= $cow['id']; ?>">

<?= htmlspecialchars($cow['tag_number']); ?>

-

<?= htmlspecialchars($cow['name']); ?>

</option>

<?php endwhile; ?>

</select>

</div>

<!-- Feed -->

<div class="col-md-6 mb-3">

<label class="form-label">

Feed Type <span class="text-danger">*</span>

</label>

<select
name="feed_id"
class="form-select"
required>

<option value="">Choose Feed</option>

<?php while($feed = mysqli_fetch_assoc($feeds)): ?>

<option value="<?= $feed['id']; ?>">

<?= htmlspecialchars($feed['feed_name']); ?>

(

<?= number_format($feed['quantity'],2); ?>

<?= htmlspecialchars($feed['unit']); ?>

Available)

</option>

<?php endwhile; ?>

</select>

</div>

<!-- Quantity -->

<div class="col-md-4 mb-3">

<label class="form-label">

Quantity Used <span class="text-danger">*</span>

</label>

<input
type="number"
name="quantity"
step="0.01"
min="0.01"
class="form-control"
required>

</div>

<!-- Feeding Time -->

<div class="col-md-4 mb-3">

<label class="form-label">

Feeding Time

</label>

<select
name="feeding_time"
class="form-select"
required>

<option value="">Select Time</option>

<option value="Morning">Morning</option>

<option value="Afternoon">Afternoon</option>

<option value="Evening">Evening</option>

</select>

</div>

<!-- Feeding Date -->

<div class="col-md-4 mb-3">

<label class="form-label">

Feeding Date

</label>

<input
type="date"
name="feeding_date"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<!-- Remarks -->

<div class="col-12 mb-4">

<label class="form-label">

Remarks

</label>

<textarea
name="remarks"
class="form-control"
rows="4"
placeholder="Optional remarks..."></textarea>

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

Record Feed Usage

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

    const quantity = document.querySelector("[name='quantity']");
    const feedingDate = document.querySelector("[name='feeding_date']");
    const cow = document.querySelector("[name='cow_id']");
    const feed = document.querySelector("[name='feed_id']");
    const feedingTime = document.querySelector("[name='feeding_time']");

    form.addEventListener("submit", function(e){

        if(cow.value === "")
        {
            alert("Please select a cow.");
            cow.focus();
            e.preventDefault();
            return;
        }

        if(feed.value === "")
        {
            alert("Please select a feed item.");
            feed.focus();
            e.preventDefault();
            return;
        }

        if(parseFloat(quantity.value) <= 0 || isNaN(parseFloat(quantity.value)))
        {
            alert("Please enter a valid quantity greater than zero.");
            quantity.focus();
            e.preventDefault();
            return;
        }

        if(feedingTime.value === "")
        {
            alert("Please select the feeding time.");
            feedingTime.focus();
            e.preventDefault();
            return;
        }

        if(feedingDate.value === "")
        {
            alert("Please select the feeding date.");
            feedingDate.focus();
            e.preventDefault();
            return;
        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>