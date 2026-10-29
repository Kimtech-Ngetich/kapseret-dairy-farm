<?php

$pageTitle = "Edit Feed Usage";
$activePage = "feed";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$error = "";

/*
|--------------------------------------------------------------------------
| GET RECORD
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']))
{
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM feed_usage
WHERE id='$id'
");

if(mysqli_num_rows($query)==0)
{
    header("Location:index.php?error=1");
    exit;
}

$usage = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id, tag_number, name
FROM cows
WHERE status='Active'
ORDER BY tag_number
");

/*
|--------------------------------------------------------------------------
| LOAD FEED INVENTORY
|--------------------------------------------------------------------------
*/

$feeds = mysqli_query($conn,"
SELECT *
FROM feed_inventory
ORDER BY feed_name
");

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if(isset($_POST['update']))
{
    $cow_id = (int)$_POST['cow_id'];
    $feed_id = (int)$_POST['feed_id'];
    $quantity = (float)$_POST['quantity'];
    $feeding_time = mysqli_real_escape_string($conn,$_POST['feeding_time']);
    $feeding_date = $_POST['feeding_date'];
    $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

    mysqli_begin_transaction($conn);

    try
    {
        /*
        --------------------------------------------------------------
        RESTORE PREVIOUS STOCK
        --------------------------------------------------------------
        */

        mysqli_query($conn,"
        UPDATE feed_inventory
        SET quantity = quantity + {$usage['quantity']}
        WHERE id='{$usage['feed_id']}'
        ");

        /*
        --------------------------------------------------------------
        GET CURRENT INVENTORY
        --------------------------------------------------------------
        */

        $feedResult = mysqli_query($conn,"
        SELECT *
        FROM feed_inventory
        WHERE id='$feed_id'
        ");

        $feed = mysqli_fetch_assoc($feedResult);

        if(!$feed)
        {
            throw new Exception("Feed not found.");
        }

        if($quantity > $feed['quantity'])
        {
            throw new Exception("Insufficient feed stock available.");
        }

        /*
        --------------------------------------------------------------
        DEDUCT NEW QUANTITY
        --------------------------------------------------------------
        */

        $remaining = $feed['quantity'] - $quantity;

        if($remaining <= 0)
        {
            $status = "Out of Stock";
        }
        elseif($remaining <= $feed['reorder_level'])
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
            quantity='$remaining',
            status='$status'
        WHERE id='$feed_id'
        ");

        /*
        --------------------------------------------------------------
        UPDATE USAGE RECORD
        --------------------------------------------------------------
        */

        mysqli_query($conn,"
        UPDATE feed_usage
        SET

        cow_id='$cow_id',

        feed_id='$feed_id',

        quantity='$quantity',

        feeding_time='$feeding_time',

        feeding_date='$feeding_date',

        remarks='$remarks'

        WHERE id='$id'
        ");

        mysqli_commit($conn);

        header("Location:index.php?updated=1");

        exit;

    }
    catch(Exception $e)
    {
        mysqli_rollback($conn);

        $error = $e->getMessage();
    }

}

?>
<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Feed Usage

</h2>

<p>

Update feed usage information. Inventory will be adjusted automatically.

</p>

</div>

<?php if(!empty($error)): ?>

<div class="alert alert-danger alert-dismissible fade show">

<i class="bi bi-exclamation-triangle-fill"></i>

<?= htmlspecialchars($error); ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php endif; ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-basket-fill"></i>

Feed Usage Details

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<!-- Cow -->

<div class="col-md-6 mb-3">

<label class="form-label">

Cow <span class="text-danger">*</span>

</label>

<select
name="cow_id"
class="form-select"
required>

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option
value="<?= $cow['id']; ?>"
<?= ($cow['id']==$usage['cow_id']) ? "selected" : ""; ?>>

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

Feed <span class="text-danger">*</span>

</label>

<select
name="feed_id"
class="form-select"
required>

<?php while($feed=mysqli_fetch_assoc($feeds)): ?>

<option
value="<?= $feed['id']; ?>"
<?= ($feed['id']==$usage['feed_id']) ? "selected" : ""; ?>>

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

Quantity Used

</label>

<input
type="number"
step="0.01"
min="0.01"
name="quantity"
class="form-control"
value="<?= $usage['quantity']; ?>"
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

<option value="Morning" <?= $usage['feeding_time']=="Morning" ? "selected":""; ?>>Morning</option>

<option value="Afternoon" <?= $usage['feeding_time']=="Afternoon" ? "selected":""; ?>>Afternoon</option>

<option value="Evening" <?= $usage['feeding_time']=="Evening" ? "selected":""; ?>>Evening</option>

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
value="<?= $usage['feeding_date']; ?>"
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
rows="4"><?= htmlspecialchars($usage['remarks']); ?></textarea>

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
name="update"
class="btn btn-warning">

<i class="bi bi-check-circle"></i>

Update Feed Usage

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

    const cow = document.querySelector("[name='cow_id']");
    const feed = document.querySelector("[name='feed_id']");
    const quantity = document.querySelector("[name='quantity']");
    const feedingTime = document.querySelector("[name='feeding_time']");
    const feedingDate = document.querySelector("[name='feeding_date']");

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

        if(isNaN(parseFloat(quantity.value)) || parseFloat(quantity.value) <= 0)
        {
            alert("Quantity must be greater than zero.");
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