<?php

$pageTitle = "Record New Sale";
$activePage = "sales";

require_once "../includes/auth_check.php";
require_once "../config/database.php";

include "../includes/header.php";
include "../includes/sidebar.php";
include "../includes/topbar.php";

$message = "";

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE COWS
|--------------------------------------------------------------------------
*/

$cows = mysqli_query($conn,"
SELECT id,name,tag_number
FROM cows
WHERE status='Active'
ORDER BY tag_number ASC
");

/*
|--------------------------------------------------------------------------
| SAVE SALE
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{

$customer_name=mysqli_real_escape_string($conn,$_POST['customer_name']);

$cow_id=(int)$_POST['cow_id'];

$sale_date=$_POST['sale_date'];

$quantity=(float)$_POST['quantity'];

$price=(float)$_POST['price_per_litre'];

$total=$quantity*$price;

$payment=mysqli_real_escape_string($conn,$_POST['payment_method']);

$notes=mysqli_real_escape_string($conn,$_POST['notes']);

$insert=mysqli_query($conn,"
INSERT INTO sales
(
customer_name,
cow_id,
sale_date,
quantity,
price_per_litre,
total_amount,
payment_method,
notes
)
VALUES
(
'$customer_name',
'$cow_id',
'$sale_date',
'$quantity',
'$price',
'$total',
'$payment',
'$notes'
)
");

if($insert){

mysqli_query($conn,"
INSERT INTO activities(activity)
VALUES('Recorded milk sale for $customer_name')
");

$message='
<div class="alert alert-success">

Sale recorded successfully.

</div>';

}else{

$message='
<div class="alert alert-danger">
<strong>Unable to save sale.</strong><br>
'.mysqli_error($conn).'
</div>';

}

}

?>

<div class="main-content">

<div class="container-fluid">

<div class="page-header">

<h2>

<i class="bi bi-cart-check"></i>

Record Milk Sale

</h2>

<p>

Create a new milk sales transaction.

</p>

</div>

<?= $message ?>

<div class="dashboard-panel">

<div class="dashboard-panel-header">

<h5>

<i class="bi bi-pencil-square"></i>

Sales Information

</h5>

</div>

<div class="dashboard-panel-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Customer Name

</label>

<input
type="text"
name="customer_name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Sale Date

</label>

<input
type="date"
name="sale_date"
class="form-control"
value="<?= date('Y-m-d') ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Cow

</label>

<select
name="cow_id"
class="form-select"
required>

<option value="">

Select Cow

</option>

<?php while($cow=mysqli_fetch_assoc($cows)): ?>

<option value="<?= $cow['id'] ?>">

<?= $cow['tag_number']." - ".$cow['name'] ?>

</option>

<?php endwhile; ?>

</select>

</div>
<div class="col-md-6 mb-3">

    <label class="form-label">

        Quantity (Litres)

    </label>

    <input
        type="number"
        step="0.01"
        min="0"
        id="quantity"
        name="quantity"
        class="form-control"
        placeholder="0.00"
        required>

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Price Per Litre (KES)

    </label>

    <input
        type="number"
        step="0.01"
        min="0"
        id="price"
        name="price_per_litre"
        class="form-control"
        placeholder="0.00"
        required>

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Total Amount (KES)

    </label>

    <input
        type="text"
        id="total"
        class="form-control"
        value="0.00"
        readonly>

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Payment Method

    </label>

    <select
        name="payment_method"
        class="form-select"
        required>

        <option value="">Select Payment Method</option>

        <option>Cash</option>

        <option>Mpesa</option>

        <option>Bank</option>

    </select>

</div>

<div class="col-12 mb-3">

    <label class="form-label">

        Notes

    </label>

    <textarea
        name="notes"
        rows="4"
        class="form-control"
        placeholder="Optional notes..."></textarea>

</div>

</div>

<div class="mt-4 d-flex gap-2">

    <button
        type="submit"
        name="save"
        class="btn btn-success">

        <i class="bi bi-check-circle"></i>

        Save Sale

    </button>

    <a
        href="records.php"
        class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Back to Records

    </a>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

const quantity=document.getElementById("quantity");

const price=document.getElementById("price");

const total=document.getElementById("total");

function calculateTotal(){

    let qty=parseFloat(quantity.value)||0;

    let pr=parseFloat(price.value)||0;

    total.value=(qty*pr).toFixed(2);

}

quantity.addEventListener("input",calculateTotal);

price.addEventListener("input",calculateTotal);

</script>

<?php include "../includes/footer.php"; ?>

</body>
</html>